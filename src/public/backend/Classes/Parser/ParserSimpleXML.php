<?

namespace App\Classes\Parser;

use App\Classes\Posts\PostCollection;
use App\Classes\Posts\Post;
use App\Intefaces\IParser;
use App\Intefaces\IParserSource;


class ParserSimpleXML extends Parser implements IParser
{

    private function loadData()
    {

        return simplexml_load_file($this->getSourcePath(), 'SimpleXMLElement', LIBXML_NOCDATA);
    }
    public function getRawData()
    {

        return $this->loadData();
    }
    public function getData()
    {
        $xml = $this->getRawData();

        $this->postCollection = new PostCollection();

        foreach ($xml->xpath($this->source->getTarget()) as $article) {
            $post = new Post();
            $post->setTitle((string)$article->title);
            $post->setCategory((string)$article->category);
            $post->setDescription((string)$article->description);
            $post->setDatePublish($article->pubDate);
            $post->setPreviewPhotoPath(($article->enclosure) ? (string)$article->enclosure->attributes()['url'] : $post->getPreviewPhotoPath());
            $this->postCollection->addPost($post);
        }
        return $this->postCollection;
    }
}
