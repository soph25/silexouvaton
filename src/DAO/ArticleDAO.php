<?php

namespace MicroCMS\DAO;

use MicroCMS\Domain\Article;

class ArticleDAO extends DAO
{
    /**
     * Return a list of all articles, sorted by date (most recent first).
     *
     * @return array A list of all articles.
     */
    public function findAll() {
        $sql = "SELECT * FROM articles ORDER BY art_id DESC";
        //$result = $this->getDb()->fetchAll($sql);
        $sth = $this->getDb()->query($sql);
		$result = $sth->fetchAll();
		$articles = array();
        foreach ($result as $row) {
            $articleId = $row['art_id'];
            $articles[$articleId] = $this->buildDomainObject($row);
        }
        #var_dump($articles);
        return $articles;
                
    }

    /**
     * Returns an article matching the supplied id.
     *
     * @param integer $id The article id.
     *
     * @return \MicroCMS\Domain\Article|throws an exception if no matching article is found
     */
    public function find($id) {
        $sql = "SELECT * FROM articles where id = :id";
		$stmt = $this->getDb()->prepare("SELECT * FROM articles where art_id = :art_id");
		$stmt->bindValue(':art_id', $id);
        $stmt->execute();
// fetching rows into array
        $row = $stmt->fetchAll();
	
        if ($row)
            return $this->buildDomainObject($row[0]);
        else
            throw new \Exception("No article matching id " . $id);
    }

    /**
     * Saves an article into the database.
     *
     * @param \MicroCMS\Domain\Article $article The article to save
     */
    public function save(Article $article) {
        $articleData = array(
            'art_title' => $article->getTitle(),
            'art_content' => $article->getContent(),
            );

        if ($article->getId()) {
            // The article has already been saved : update it
            //$this->getDb()->update('articles', $articleData, array('art_id' => $article->getId()));
			$sql = "UPDATE articles SET art_title = :art_title, art_content = :art_content WHERE art_id = :art_id";
			$stmt = $this->getDb()->prepare($sql);
			$stmt->bindValue(':art_id', $article->getId());
			$stmt->bindValue(':art_title', $articleData['art_title']);
			$stmt->bindValue(':art_content', $articleData['art_content']);
			$stmt->execute();
        } else {
            // The article has never been saved : insert it
            //$this->getDb()->insert('articles', $articleData);
			$sql = "INSERT INTO articles (art_title, art_content) VALUES (:art_title, :art_content)";
			//$stmt->bindValue(':art_id', $article->getId());
			$stmt = $this->getDb()->prepare($sql);
			$stmt->bindValue(':art_title', $articleData['art_title']);
			$stmt->bindValue(':art_content', $articleData['art_content']);
			$stmt->execute();
            // Get the id of the newly created article and set it on the entity.
            $id = $this->getDb()->lastInsertId();
            $article->setId($id);
        }
    }

    /**
     * Removes an article from the database.
     *
     * @param integer $id The article id.
     */
    public function delete($id) {
        // Delete the article
        $this->getDb()->delete('articles', array('art_id' => $id));
    }

    /**
     * Creates an Article object based on a DB row.
     *
     * @param array $row The DB row containing Article data.
     * @return \MicroCMS\Domain\Article
     */

    protected function buildDomainObject(array $row) {
        $article = new Article();
        $article->setId($row['art_id']);
        #$article->setDateAjout($row['dateAjout']);
        //$article->setDateAjout(new \DateTime($row['dateAjout']));
        $article->setTitle($row['art_title']);
        $article->setContent($row['art_content']);
        return $article;
    }
}
