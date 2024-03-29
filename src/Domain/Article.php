<?php

namespace MicroCMS\Domain;

class Article 
{
    /**
     * Article id.
     *
     * @var integer
     */
    private $id;

    /**
     * Article title.
     *
     * @var string
     */
    private $title;

    /**
     * Article content.
     *
     * @var string
     */
    private $content;

    /**
     * Article date.
     *
     * @var datetime
     */ 

    private $dateAjout;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    public function getDateAjout(){
        
        
        return $this->dateAjout;
    }

    public function setDateAjout(\DateTime $dateAjout){
         
         return $this->dateAjout = $dateAjout;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
        return $this;
    }
}
