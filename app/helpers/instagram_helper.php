<?php
function hashcheck(){
    if(EX == 1){
        return false;
    }else{
        return true;
    }
}

function Instagram_Get_Avatar($username){
    try{
        $sites_html = file_get_contents('https://www.instagram.com/'.$username);

        $html = new DOMDocument();
        @$html->loadHTML($sites_html);
        $meta_og_img = null;
        //Get all meta tags and loop through them.
        foreach($html->getElementsByTagName('meta') as $meta) {
            //If the property attribute of the meta tag is og:image
            if($meta->getAttribute('property')=='og:image'){ 
                //Assign the value from content attribute to $meta_og_img
                $meta_og_img = $meta->getAttribute('content');
            }
        }
        return $meta_og_img;
    }catch(Exception $e){
        return BASE."assets/images/noavatar.png";
    }
}

if(!function_exists("Instagram_Loader")){
    function Instagram_Loader($username, $password){
        listIt(APPPATH. "libraries/InstagramAPI/");
        $i = new Instagram(false, false);
        $i->setUser($username, $password);
        $CI = &get_instance();
        try {
            $i->timelineFeed();
            $CI->db->update(INSTAGRAM_ACCOUNTS, array("checkpoint" => 0), array("username" => $username));
        } catch(InstagramException $e){
            if (strpos($e->getMessage(), 'login_required') !== false || strpos($e->getMessage(), 'login-enforced') !== false) {
                try {
                    $result = $i->login(true);
                    $CI->db->update(INSTAGRAM_ACCOUNTS, array("checkpoint" => 0), array("username" => $username));
                } catch (Exception $k) {
                    
                    if(strpos($k->getMessage(), 'checkpoint') !== false || strpos($k->getMessage(), 'Please wait a few minutes before you try again') !== false){
                        $CI->db->update(INSTAGRAM_ACCOUNTS, array("checkpoint" => 1), array("username" => $username));
                    }
                }
                
            }
        }
        return $i;
    }
}

if(!function_exists("Instagram_Login")){
    function Instagram_Login($username, $password){
        try {
            Delete(APPPATH."libraries/InstagramAPI/data/".$username);
            $i = Instagram_Loader($username, $password);
            $data = $i->login(true);
            return $i;
        }
        catch ( Exception $e ) {
            $error_arr = $e->getTrace();
            $txt  = $error_arr[0]['args'][0]->error_title;
            $type = $error_arr[0]['args'][0]->error_type;
            if(strpos($type, 'checkpoint') !== false){
                $txt = l("Please go to <a href='http://instagram.com/' target='_blank'>http://instagram.com/</a> to verify email and then login at here again");
            }
            return array(
                "txt"   => ($txt != "")?$txt:$type,
                "type"  => $type,
                "label" => "bg-red",
                "st"    => "error",
            );
        }
    }
}

if(!function_exists("Instagram_Search_Hashtags")){
    function Instagram_Search_Hashtags($data, $hashtag){
        $i = Instagram_Loader($data->username, $data->password);
        try{
            $result = $i->searchTags($hashtag);
            return $result;
        }catch(InstagramException $e){
            return $e->getMessage();
        }
    }
}

if(!function_exists("Instagram_Search_Locations")){
    function Instagram_Search_Locations($data, $lat, $lng,  $keyword){
        $i = Instagram_Loader($data->username, $data->password);
        try{
            $result = $i->searchLocation($lat, $lng, $keyword);
            return $result;
        }catch(InstagramException $e){
            return $e->getMessage();
        }
    }
}

if(!function_exists("Instagram_Search_Usernames")){
    function Instagram_Search_Usernames($data, $username){
        $i = Instagram_Loader($data->username, $data->password);
        try{
            $result = $i->searchUsers($username);
            return $result;
        }catch(InstagramException $e){
            return $e->getMessage();
        }
    }
}

if(!function_exists("Instagram_Sort_Tags")){
    function Instagram_Sort_Tags($data){
        usort($data, function($a, $b) {
            if($a->media_count==$b->media_count) return 0;
            return $a->media_count < $b->media_count?1:-1;
        });
        return $data;
    }
}

if (!function_exists('Instagram_Get_Id')) {
    function Instagram_Get_Id($url){
        $link = str_replace("https://", "", $url);
        $link = str_replace("http://", "", $link);
        $link = explode("/", $link);
        if(count($link) >= 3){
            $url = $link[2];
        }else{
            $url = $url;
        }
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'https://api.instagram.com/oembed/?url=http://instagram.com/p/'.$url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($curl, CURLOPT_HEADER, false);
        $data = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($data);
        if(!empty($result)){
            return $result->media_id;
        }else{
            return false;
        }
    }
}

if(!function_exists("Instagram_Get_Feed")){
    function Instagram_Get_Feed($i, $type, $keyword = ""){
        $result = false;
        try {
            switch ($type) {
                case 'timeline':
                    $timeline_feed = $i->timelineFeed();
                    $result = array();
                    $feeds  = $timeline_feed->feed_items;
                    if(!empty($feeds)){
                        foreach ($feeds as $key => $row) {
                            if(isset($row->media_or_ad)){
                                $result[] = $row->media_or_ad;
                            }
                        }
                    }
                    break;
                case 'popular':
                    $result = $i->getPopularFeed();
                    if($result->status == "ok"){
                        $result = $result->items;
                    }
                    break;
                case 'explore_tab':
                    $explode_feed = $i->explore();
                    if($explode_feed->status == "ok"){
                        $result = array();
                        $feeds = $explode_feed->items;
                        if(!empty($feeds)){
                            foreach ($feeds as $key => $row) {
                                if(isset($row->media)){
                                    $result[] = $row->media;
                                }
                            }
                        }
                    }
                    break;
                case 'reels_tray':
                    $reels_tray_feed = $i->getReelsTrayFeed();
                    if($reels_tray_feed->status == "ok"){
                        $result = $reels_tray_feed->tray[0]->items;
                    }
                    break;
                case 'your_feed':
                    $self_user_feed = $i->getSelfUserFeed();
                    if($self_user_feed->status == "ok"){
                        $result = $self_user_feed->items;
                    }
                    break;
                case 'tag':
                    $hashtag_feed = $i->getHashtagFeed($keyword);
                    if($hashtag_feed->status == "ok"){
                        $result = $hashtag_feed->items;
                    }
                    break;
                case 'search_tags':
                    $search_tags = $i->searchTags($keyword);
                    if($search_tags->status == "ok"){
                        $result = Instagram_Sort_Tags($search_tags->results);
                    }
                    break;
                case 'search_users':
                    $search_users = $i->searchUsers($keyword);
                    if($search_users->status == "ok"){
                        $result = $search_users->users;
                    }
                    break;
                case 'following':
                    $following = $i->getSelfUsersFollowing();
                    if($following->status == "ok"){
                        $result = $following->users;
                    }
                    break;
                case 'followers':
                    $followers = $i->getSelfUserFollowers();
                    if($followers->status == "ok"){
                        $result = $followers->users;
                    }
                    break;
                case 'feed':
                    $mediaId   = Instagram_Get_Id($keyword);
                    if($mediaId != ""){
                        $feed      = $i->mediaInfo($mediaId);
                        if($feed->status == "ok"){
                            $result = $feed->items[0];
                        }
                    }
                    break;
                case 'feed_by_id':
                    $feed = $i->mediaInfo($keyword);
                    if($feed->status == "ok"){
                        $result = $feed->items[0];
                    }
                    break;
                case 'user_feed':
                    $array_username = explode("|", $keyword);
                    if(count($array_username) == 2){
                        $user_feed = $i->getUserFeed($array_username[0]);
                        if($user_feed->status == "ok"){
                            $result = $user_feed->items;
                        }
                    }
                    break;
                case 'user_following':
                    $array_username = explode("|", $keyword);
                    if(count($array_username) == 2){
                        $following = $i->getUserFollowings($array_username[0]);
                        if($following->status == "ok"){
                            $result = $following->users;
                        }
                    }
                    break;
                case 'user_followers':
                    $array_username = explode("|", $keyword);
                    if(count($array_username) == 2){
                        $followers = $i->getUserFollowers($array_username[0]);
                        if($followers->status == "ok"){
                            $result = $followers->users;
                        }
                    }
                    break;
                case 'following_recent_activity':
                    $followback = $i->getRecentActivity();
                    if($followback->status == "ok"){
                        $result = array();
                        $list = $followback->old_stories;
                        foreach ($list as $key => $row) {
                            if(isset($row->args->inline_follow) && $row->args->inline_follow->following != 1 && $row->args->inline_follow->outgoing_request != 1 && strpos($row->args->text, 'started following you') !== false ){
                                $result[] = $row->args->inline_follow->user_info;
                            }
                        }
                    }
                    break;

                case 'location':
                    $array_location = explode("|", $keyword);
                    if(count($array_location) == 4){
                        $location = $i->getLocationFeed($array_location[3]);
                        if($location->status == "ok"){
                            $result = $location->items;
                        }
                    }
                case 'username':
                    $follow_types  = array("user_following","user_followers");
                    $follow_index  = array_rand($follow_types);
                    $follow_type   = $follow_types[$follow_index];
                    switch ($follow_type) {
                        case 'user_following':
                            $array_username = explode("|", $keyword);
                            if(count($array_username) == 2){
                                $following = $i->getUserFollowings($array_username[0]);
                                if($following->status == "ok"){
                                    $result = $following->users;
                                }
                            }
                            break;
                        case 'user_followers':
                            $array_username = explode("|", $keyword);
                            if(count($array_username) == 2){
                                $followers = $i->getUserFollowers($array_username[0]);
                                if($followers->status == "ok"){
                                    $result = $followers->users;
                                }
                            }
                            break;
                    }
                    break;
            }
        } catch (Exception $e){
            $result = $e->getMessage();
        }
        
        return $result;
    }
}

if(!function_exists("Instagram_Genter")){
    function Instagram_Genter($fullname){
        $result = file_get_contents('https://api.genderize.io/?name='.urlencode($fullname));
        pr($result);
    }
}

if(!function_exists('Instagram_Filter')){
    function Instagram_Filter($data = array(), $filter = array(), $timezone = "", $type = "feed"){
        
        $filter = json_decode($filter);
        $result = array();
        if(!empty($filter) && !empty($data)){
            switch ($type) {
                case 'feed':
                    foreach ($data as $key => $row) {

                        //Media age
                        if($filter->media_age != "" && $timezone != ""){
                            if(isset($row->caption->created_at_utc)){
                                $time_media = "";
                                switch ($filter->media_age) {
                                    case 'new':
                                        $time_media = 600;
                                    case '1h':
                                        $time_media = 3600;
                                    case '12h':
                                        $time_media = 3600;
                                    case '1d':
                                        $time_media = 3600;
                                    case '3d':
                                        $time_media = 3600;
                                    case '1w':
                                        $time_media = 3600;
                                        break;
                                    case '2w':
                                        $time_media = 3600;
                                        break;
                                    case '1M':
                                        $time_media = 3600;
                                        break;
                                }
                                if($time_media != ""){
                                    $time_now  = strtotime(NOW);
                                    $date = new DateTime(date("Y-m-d H:i:s", $time_now), new DateTimeZone(TIMEZONE_SYSTEM));
                                    $date->setTimezone(new DateTimeZone($timezone));
                                    $time_of_user = $date->format('Y-m-d H:i:s');
                                    if(strtotime($time_of_user) - $row->caption->created_at_utc > $time_media){
                                        unset($data[$key]);
                                        continue;
                                    }
                                }
                            }
                        }

                        //Media type
                        switch ($filter->media_type) {
                            case 'photo':
                                if($row->media_type == 2){
                                    unset($data[$key]);
                                    continue;
                                }
                                break;
                            
                            case 'video':
                                if($row->media_type == 1){
                                    unset($data[$key]);
                                    continue;
                                }
                                break;
                        }

                        //Min. likes filter
                        if($row->like_count < $filter->min_likes && $filter->min_likes != 0){
                            unset($data[$key]);
                            continue;
                        }

                        //Max. likes filter
                        if($row->like_count > $filter->max_likes && $filter->max_likes != 0){
                            unset($data[$key]);
                            continue;
                        }

                        //Min. comments filter
                        if(isset($row->comment_count) && $row->comment_count < $filter->min_comments && $filter->min_comments != 0){
                            unset($data[$key]);
                            continue;
                        }

                        if(isset($row->comments_disabled) && $row->comments_disabled == 1 && $filter->min_comments != 0){
                            unset($data[$key]);
                            continue;
                        }

                        //Max. comments filter
                        if(isset($row->comment_count) && $row->comment_count > $filter->max_comments && $filter->max_comments != 0){
                            unset($data[$key]);
                            continue;
                        }

                        if(isset($row->comments_disabled) && $row->comments_disabled == 1 && $filter->max_comments != 0){
                            unset($data[$key]);
                            continue;
                        }

                        //User relation filter
                        switch ($filter->user_relation) {
                            case 'followers':
                                if(isset($row->user->friendship_status) && is_object($row->user->friendship_status) && isset($row->user->friendship_status->followed_by) && $row->user->friendship_status->followed_by != ""){
                                    unset($data[$key]);
                                    continue;
                                }
                                break;

                            case 'followings':
                                if(isset($row->user->friendship_status) && is_object($row->user->friendship_status) && isset($row->user->friendship_status->following) && $row->user->friendship_status->following != ""){
                                    unset($data[$key]);
                                    continue;
                                }
                                break;

                            case 'both':
                                if(isset($row->user->friendship_status) && is_object($row->user->friendship_status) && isset($row->user->friendship_status->followed_by) && $row->user->friendship_status->followed_by != ""){
                                    unset($data[$key]);
                                    continue;
                                }

                                if(isset($row->user->friendship_status) && is_object($row->user->friendship_status) && isset($row->user->friendship_status->following) && $row->user->friendship_status->following != ""){
                                    unset($data[$key]);
                                    continue;
                                }
                                break;
                        }
                    }
                    break;
                
                case 'user':
                    
                    break;
            }
        }
        return $data;
    }
}

if(!function_exists('Instagram_Filter_Item')){
    function Instagram_Filter_Item($data = array(), $filter = array(), $type = "feed", $i){
        $id = isset($data->pk)?$data->pk:$data->id;
        $userinfo = @$i->getUsernameInfo($id);
        $filter   = json_decode($filter);
        if(!empty($filter) && !empty($data)){
            switch ($type) {
                case 'feed':
                    # code...
                    break;
                case 'user':
                    //User profile filter
                    switch ($filter->user_profile) {
                        case 'low':
                            if($userinfo->user->profile_pic_id == "" || (int)$userinfo->user->media_count == 0){
                                return false;
                            }
                            break;
                        case 'medium':
                            if($userinfo->user->profile_pic_id == "" || (int)$userinfo->user->media_count < 10 || $userinfo->user->full_name == ""){
                                return false;
                            }
                            break;
                        case 'height':
                            if($userinfo->user->profile_pic_id == "" || (int)$userinfo->user->media_count < 30 || $userinfo->user->full_name == "" || $userinfo->user->biography == ""){
                                return false;
                            }
                            break;
                    }

                    //Min. followers filter
                    if($userinfo->user->follower_count < $filter->min_followers && $filter->min_followers != 0){
                        return false;
                    }

                    //Max. followers filter
                    if($userinfo->user->follower_count > $filter->max_followers && $filter->max_followers != 0){
                        return false;
                    }

                    //Min. following filter
                    if($userinfo->user->following_count < $filter->min_followings && $filter->min_followings != 0){
                        return false;
                    }

                    //Max. follow filter
                    if($userinfo->user->following_count > $filter->max_followings && $filter->max_followings != 0){
                        return false;
                    }
                    break;
            }
        }
        return $data;
    }
}

if(!function_exists("Instagram_Get_Follow")){
    function Instagram_Get_Follow($i, $type, $limit = 0){
        $result = false;
        try {
            switch ($type) {
                case 'following':
                    $data = array();
                    $next_page = null;
                    while(count($data) <= 15) {
                        $following = $i->getSelfUsersFollowing($next_page);
                        if($following->status == "ok"){
                            $next_page = $following->next_max_id;
                            $data = array_merge($data, $following->users);
                            if($following->next_max_id == ""){
                                break;
                            }

                        }
                    } 
                    $result = $data;
                    break;
                case 'followers':
                    $data = array();
                    $next_page = null;
                    while(count($data) <= $limit) {
                        $followers = $i->getSelfUserFollowers($next_page);
                        if($followers->status == "ok"){
                            $next_page = $followers->next_max_id;
                            $data = array_merge($data, $followers->users);
                            if($followers->next_max_id == ""){
                                break;
                            }

                        }
                    } 
                    $result = $data;
                    break;
            }
        } catch (Exception $e){
            $result = $e->getMessage();
        }
        return $result;
    }
}

if(!function_exists("Instagram_Post")){
    function Instagram_Post($data){
        $spintax = new Spintax();
        $CI = &get_instance();
        $response = array();
        $i = Instagram_Loader($data->username, $data->password);
        if(!is_string($i)){
            switch ($data->category) {
                case 'post':
                    switch ($data->type) {
                        case 'photo':
                            try {
                                $response =$i->uploadPhoto($data->image, $data->message);
                            } catch (Exception $e){
                                $response = $e->getMessage();
                            }

                            break;
                        case 'story':
                            try {
                                $response =$i->uploadPhotoStory($data->image, $data->message);
                            } catch (Exception $e){
                                $response = $e->getMessage();
                            }

                            break;
                        case 'video':
                            $url = $data->image;
                            $id = getIdYoutube($data->image);
                            if(strlen($id) == 11){
                                parse_str(file_get_contents('http://www.youtube.com/get_video_info?video_id='.$id),$info);
                                if($info['status'] == "ok"){
                                    $streams = explode(',',$info['url_encoded_fmt_stream_map']);
                                    $type = "video/mp4"; 
                                    foreach($streams as $stream){ 
                                        parse_str($stream,$real_stream);
                                        $stype = $real_stream['type'];
                                        if(strpos($real_stream['type'],';') !== false){
                                            $tmp = explode(';',$real_stream['type']);
                                            $stype = $tmp[0]; 
                                            unset($tmp); 
                                        } 
                                        if($stype == $type && ($real_stream['quality'] == 'large' || $real_stream['quality'] == 'medium' || $real_stream['quality'] == 'small')){
                                            try {
                                                $response =$i->uploadVideo($real_stream['url'].'&signature='.@$real_stream['sig'], $data->message);
                                                if(isset($response->fullResponse)){
                                                    $response = $response->fullResponse;
                                                }
                                            } catch (Exception $e){
                                                $response = $e->getMessage();
                                            }
                                        }
                                    }
                                }else{
                                    $response = array(
                                        "status"  => "fail",
                                        "message" => strip_tags($info['reason'])
                                    );
                                }
                            }else{
                                if (strpos($url, 'facebook.com') != false) {
                                    $url = fbdownloadVideo($url);
                                }

                                try {
                                    $response =$i->uploadVideo($url, $data->message);
                                    if(isset($response->fullResponse)){
                                        $response = $response->fullResponse;
                                    }
                                } catch (Exception $e){
                                    $response = $e->getMessage();
                                }
                            }

                            break;
                    }

                    if(isset($response->status) && $response->status == "ok"){
                        $response = array(
                            "st"      => "success",
                            "id"      => $response->media->pk,
                            "code"    => $response->media->code,
                            "txt"     => l('Post successfully')
                        );
                    }

                    if(is_string($response)){
                        $response = array(
                            "st"      => "error",
                            "txt"     => $response
                        );
                    }
                    return $response;
                    break;

                case 'like':
                    $target       = array_rand((array)json_decode($data->title));

                    $tags             = (array)json_decode($data->description);
                    $tag_index        = array_rand((array)json_decode($data->description));

                    $locations        = (array)json_decode($data->url);
                    $location_index   = array_rand((array)json_decode($data->url));

                    $usernames        = (array)json_decode($data->image);
                    $username_index   = array_rand((array)json_decode($data->image));

                    $tag              = @$spintax->process($tags[$tag_index]);
                    $location         = @$spintax->process($locations[$location_index]);
                    $username         = @$spintax->process($usernames[$username_index]);

                    switch ($target) {
                        case 'location':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $location);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->code)->where("type", $data->category)->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                                    if(empty($history)){
                                        $like = $i->like($feed->pk);
                                        //echo "<a href='https://instagram.com/p/".$feed->code."' target='_blank'>".$feed->code."</a>";
                                        if($like->status == "ok"){
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 'username':
                            try {
                                $feeds  = Instagram_Get_Feed($i, "user_feed", $username);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->code)->where("type", $data->category)->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                                    if(empty($history)){
                                        $like = $i->like($feed->pk);
                                        //echo "<a href='https://instagram.com/p/".$feed->code."' target='_blank'>".$feed->code."</a>";
                                        if($like->status == "ok"){
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                        
                        case 'tag':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $tag);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = @array_rand($feeds);
                                    $feed   = @$feeds[$index];
                                    $history = $CI->db->select("*")->where("pk", $feed->code)->where("type", $data->category)->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                                    if(empty($history)){
                                        $like = $i->like($feed->pk);
                                        //echo "<a href='https://instagram.com/p/".$feed->code."' target='_blank'>".$feed->code."</a>";
                                        if($like->status == "ok"){
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }

                    return $response;
                    break;

                case 'comment':
                    $target       = array_rand((array)json_decode($data->title));

                    $tags             = (array)json_decode($data->description);
                    $tag_index        = array_rand((array)json_decode($data->description));

                    $locations        = (array)json_decode($data->url);
                    $location_index   = array_rand((array)json_decode($data->url));

                    $usernames        = (array)json_decode($data->image);
                    $username_index   = array_rand((array)json_decode($data->image));

                    $comments         = (array)json_decode($data->comment);
                    $comment_index    = array_rand((array)json_decode($data->comment));

                    $tag              = @$spintax->process($tags[$tag_index]);
                    $location         = @$spintax->process($locations[$location_index]);
                    $username         = @$spintax->process($usernames[$username_index]);
                    $comment          = @$spintax->process($comments[$comment_index]);

                    switch ($target) {
                        case 'location':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $location);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    //$feed   = Instagram_Filter_Item($feed->user, $data->filter, 'user', $i);
                                    $history = $CI->db->select("*")->where("pk", $feed->code)->where("type", $data->category)->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                                    if(empty($history)){
                                        $comment = $i->comment($feed->pk, $comment);
                                        //echo "<a href='https://instagram.com/p/".$feed->code."' target='_blank'>".$feed->code."</a>";
                                        if($comment->status == "ok"){
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 'username':
                            try {
                                $feeds  = Instagram_Get_Feed($i, "user_feed", $username);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    //$feed   = Instagram_Filter_Item($user, $data->filter, 'user', $i);
                                    $history = $CI->db->select("*")->where("pk", $feed->code)->where("type", $data->category)->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                                    if(empty($history)){
                                        $comment = $i->comment($feed->pk, $comment);
                                        //echo "<a href='https://instagram.com/p/".$feed->code."' target='_blank'>".$feed->code."</a>";
                                        if($comment->status == "ok"){
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                        
                        case 'tag':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $tag);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");

                                if(!empty($feeds)){
                                    $index  = @array_rand($feeds);
                                    $feed   = @$feeds[$index];
                                    //$feed   = Instagram_Filter_Item($feed->user, $data->filter, 'user', $i);
                                    $history = $CI->db->select("*")->where("pk", $feed->code)->where("type", $data->category)->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                                    if(empty($history)){
                                        $comment = $i->comment($feed->pk, $comment);
                                        //echo "<a href='https://instagram.com/p/".$feed->code."' target='_blank'>".$feed->code."</a>";
                                        if($comment->status == "ok"){
                                            $response = array(
                                                "st"      => "success",
                                                "data"    => json_encode($feed),
                                                "code"    => $feed->code,
                                                "txt"     => l('Successfully')
                                            );
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }

                    return $response;
                    break;

                case 'follow':
                    $target            = array_rand((array)json_decode($data->title));
                    
                    $tags             = (array)json_decode($data->description);
                    $tag_index        = array_rand((array)json_decode($data->description));

                    $locations        = (array)json_decode($data->url);
                    $location_index   = array_rand((array)json_decode($data->url));

                    $usernames        = (array)json_decode($data->image);
                    $username_index   = array_rand((array)json_decode($data->image));

                    $tag              = @$spintax->process($tags[$tag_index]);
                    $location         = @$spintax->process($locations[$location_index]);
                    $username         = @$spintax->process($usernames[$username_index]);    

                    switch ($target) {
                        case 'location':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $location);
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                    $user   = Instagram_Filter_Item($feed->user, $data->filter, 'user', $i);
                                    if(!empty($user)){
                                        if($user->friendship_status->following == "" && $user->friendship_status->outgoing_request == ""){
                                            $follow = $i->follow($user->pk);
                                            //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                            if($follow->status == "ok"){
                                                $response = array(
                                                    "st"      => "success",
                                                    "data"    => json_encode($user),
                                                    "code"    => $user->username,
                                                    "txt"     => l('Successfully')
                                                );
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 'username':
                            try {
                                $follow_types  = array("user_following","user_followers");
                                $follow_index  = array_rand($follow_types);
                                $follow_type   = $follow_types[$follow_index];

                                $users  = Instagram_Get_Feed($i, $follow_type, $username);
                                if(!empty($users)){
                                    $index  = array_rand($users);
                                    $user   = $users[$index];
                                    $user   = Instagram_Filter_Item($user, $data->filter, 'user', $i);
                                    if(!empty($user)){
                                        $info   = $i->userFriendship($user->pk);
                                        if($info->status == "ok"){
                                            if($info->following == "" && $info->outgoing_request == ""){
                                                $follow = $i->follow($user->pk);
                                                //echo "<a href='https://instagram.com/".$user->user->username."' target='_blank'>".$user->user->username."</a>";
                                                if($follow->status == "ok"){
                                                    $response = array(
                                                        "st"      => "success",
                                                        "data"    => json_encode($user),
                                                        "code"    => $user->username,
                                                        "txt"     => l('Successfully')
                                                    );
                                                }
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                        
                        case 'tag':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $tag);

                                if(!empty($feeds)){
                                    $index  = @array_rand($feeds);
                                    $feed   = @$feeds[$index];
                                    $user   = Instagram_Filter_Item($feed->user, $data->filter, 'user', $i);
                                    if(!empty($user)){
                                        if($user->friendship_status->following == "" && $user->friendship_status->outgoing_request == ""){
                                            $follow = $i->follow($user->pk);
                                            //echo "<a href='https://instagram.com/".$feed->user->username."' target='_blank'>".$feed->user->username."</a>";
                                            if($follow->status == "ok"){
                                                $response = array(
                                                    "st"      => "success",
                                                    "data"    => json_encode($user),
                                                    "code"    => $user->username,
                                                    "txt"     => l('Successfully')
                                                );
                                            }
                                        }
                                    }
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }
                    return $response;
                    break;

                case 'followback':
                    try {
                        $users  = Instagram_Get_Feed($i, "following_recent_activity");
                        if(!empty($users)){
                            foreach ($users as $user) {
                                $user   = Instagram_Filter_Item($user, $data->filter, 'user', $i);
                                if(!empty($user)){
                                    $info   = $i->userFriendship($user->pk);
                                    if($info->status == "ok"){
                                        if($info->following == "" && $info->outgoing_request == ""){
                                            $follow = $i->follow($user->pk);
                                            //echo "<a href='https://instagram.com/".$user->username."' target='_blank'>".$user->username."</a>";
                                            if($follow->status == "ok"){
                                                $messages         = (array)json_decode($data->message);
                                                $message_index    = array_rand((array)json_decode($data->message));
                                                if(!empty($messages)){
                                                    $message          = $spintax->process($messages[$message_index]);
                                                    if($message != ""){
                                                        $i->direct_message($user->pk, $message);
                                                    }
                                                }

                                                $response = array(
                                                    "st"      => "success",
                                                    "data"    => json_encode($user),
                                                    "code"    => $user->username,
                                                    "txt"     => l('Successfully')
                                                );
                                            }
                                            break;
                                        }
                                    }
                                }
                            }

                        }
                    } catch (Exception $e){
                        $response = array(
                            "st"      => "error",
                            "txt"     => $e->getMessage()
                        );
                    }
                    return $response;
                    break;

                case 'unfollow':
                    try {
                        $users  = Instagram_Get_Feed($i, 'following');
                        if(!empty($users)){
                            $index  = array_rand($users);
                            $user   = $users[$index];
                            $unfollow = $i->unfollow($user->pk);
                            //echo "<a href='https://instagram.com/".$user->username."' target='_blank'>".$user->username."</a>";
                            if($unfollow->status == "ok"){
                                $response = array(
                                    "st"      => "success",
                                    "data"    => json_encode($user),
                                    "code"    => $user->username,
                                    "txt"     => l('Successfully')
                                );
                            }
                        }
                    } catch (Exception $e){
                        $response = array(
                            "st"      => "error",
                            "txt"     => $e->getMessage()
                        );
                    }
                    return $response;
                    break;

                case 'deletemedia':
                    try {
                        $feeds  = Instagram_Get_Feed($i, "your_feed", "");
                        $index  = @array_rand($feeds);
                        $feed   = @$feeds[$index];
                        if(!empty($feed)){
                            $delete = $i->deleteMedia($feed->id);
                            //echo "<a href='https://instagram.com/".$feed->code."' target='_blank'>".$feed->code."</a>";
                            if($delete->status == "ok"){
                                $response = array(
                                    "st"      => "success",
                                    "data"    => json_encode($feed),
                                    "code"    => $feed->code,
                                    "txt"     => l('Successfully')
                                );
                            }
                        }
                    } catch (Exception $e){
                        $response = array(
                            "st"      => "error",
                            "txt"     => $e->getMessage()
                        );
                    }   

                    return $response;
                    break;

                case 'repost':
                    $target            = array_rand((array)json_decode($data->title));
                    
                    $tags             = (array)json_decode($data->description);
                    $tag_index        = array_rand((array)json_decode($data->description));

                    $locations        = (array)json_decode($data->url);
                    $location_index   = array_rand((array)json_decode($data->url));

                    $usernames        = (array)json_decode($data->image);
                    $username_index   = array_rand((array)json_decode($data->image));

                    $tag              = @$spintax->process($tags[$tag_index]);
                    $location         = @$spintax->process($locations[$location_index]);
                    $username         = @$spintax->process($usernames[$username_index]);

                    $feed             = array();
                    switch ($target) {
                        case 'location':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $location);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;

                        case 'username':
                            try {
                                $feeds  = Instagram_Get_Feed($i, 'user_feed', $username);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds) && is_array($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                        
                        case 'tag':
                            try {
                                $feeds  = Instagram_Get_Feed($i, $target, $tag);
                                $feeds  = Instagram_Filter($feeds, $data->filter, $data->timezone, "feed");
                                if(!empty($feeds)){
                                    $index  = array_rand($feeds);
                                    $feed   = $feeds[$index];
                                }
                            } catch (Exception $e){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $e->getMessage()
                                );
                            }
                            break;
                    }

                    if(isset($feed) && !empty($feed)){
                        $CI = &get_instance();
                        $history = $CI->db->select("*")->where("pk", $feed->pk)->where("type", "repost")->where("account_id", $data->account_id)->get(INSTAGRAM_HISTORY)->row();
                        if(empty($history)){
                            switch ($feed->media_type) {
                                case 1:
                                    try {
                                        $response =$i->uploadPhoto($feed->image_versions2->candidates[0]->url, $feed->caption->text);
                                    } catch (Exception $e){
                                        $response = $e->getMessage();
                                    }

                                    break;
                                case 2:
                                    try {
                                        $response =$i->uploadVideo($feed->video_versions[0]->url,  $feed->caption->text);
                                        if(isset($response->fullResponse)){
                                            $response = $response->fullResponse;
                                        }
                                    } catch (Exception $e){
                                        $response = $e->getMessage();
                                    }
                                    break;
                            }

                            if(isset($response->status) && $response->status == "ok"){
                                $response = array(
                                    "st"      => "success",
                                    "pk"      => $feed->pk,
                                    "data"    => json_encode($feed),
                                    "code"    => $feed->pk,
                                    "txt"     => l('Successfully')
                                );
                            }

                            if(is_string($response)){
                                $response = array(
                                    "st"      => "error",
                                    "txt"     => $response
                                );
                            }
                        }

                    }
                    
                    return $response;
                    break;

                case 'message':
                    try {
                        $message = $i->direct_message($data->group_id, $spintax->process($data->message));
                        if($message == ""){
                            $response = array(
                                "st"      => "success",
                                "code"    => $data->name,
                                "txt"     => l('Successfully')
                            );
                        }
                    } catch (Exception $e){
                        $response = $e->getMessage();
                    }

                    if(is_string($response)){
                        $response = array(
                            "st"      => "error",
                            "txt"     => $response
                        );
                    }
                    return $response;
                    break;
            }
        }else{
            $response["message"] = "Upload faild, Please try again";
            $response = array(
                "st"  => "error",
                "message" => $response["message"]
            );
        }
    }

    function removeElementWithValue($array, $key, $value){
        $array = (array)$array;
         foreach($array as $subKey => $subArray){
            $subArray = (array)$subArray;
              if($subArray[$key] != $value){
                   unset($array[$subKey]);
              }
         }
         return $array;
    }
}
?>