<?php



class SearchTagResponse extends Response
{
    public $has_more;
    public $status;

    /**
     * @var Tag[]
     */
    public $results;
}
