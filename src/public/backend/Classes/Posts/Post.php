<?

namespace App\Classes\Posts;

use App\Intefaces\IPost;
use App\Classes\Base\Words;
use App\Classes\Base\DateDiff;


class Post implements IPost
{
    private $title, $publishDate, $description, $category;
    private $previewPhotoPath = "https://pbs.twimg.com/profile_images/690184702054002689/NX6Pdj3H.jpg";
    private Words $words;

    public function getTitle()
    {
        return $this->title;
    }
    public function getDescription()
    {
        return $this->description;
    }
    public function getCategory()
    {
        return $this->category;
    }
    public function getPreviewPhotoPath()
    {
        return $this->previewPhotoPath;
    }
    public function getDatePublish()
    {
        return $this->publishDate;
    }
    public function getWords()
    {
        return $this->words->getAllWords();
    }
    public function getPopularWords($quantity = 1)
    {
        return $this->words->getMostPopular($quantity);
    }
    public function setTitle($title)
    {
        $this->title = $title;
    }
    public function setDescription($description)
    {
        $this->description = $description;
        $this->words = new Words();
        $this->words->addWordsFromString((string)$description);
    }
    public function setCategory($category)
    {
        $this->category = $category;
    }
    public function setPreviewPhotoPath($previewPhotoPath)
    {
        $this->previewPhotoPath = $previewPhotoPath;
    }
    public function setDatePublish($publishDate)
    {
        $this->publishDate = new \DateTime($publishDate);
    }
    public function getDateDiffInfo()
    {
        return ["datePublish" => $this->getDatePublish()->format('d.m.Y H:i:s'), "diff" => (new DateDiff)->getCommonDiff([new \DateTime('NOW'), $this->getDatePublish()], '%d')];
    }
}
