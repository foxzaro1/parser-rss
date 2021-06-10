<?

namespace App\Classes\Posts;

use App\Classes\Base\Categories;
use App\Intefaces\IPost;
use App\Intefaces\IPostCollection;

class PostCollection implements IPostCollection
{

    private $posts = [];

    public function getAllPosts()
    {
        return $this->posts;
    }

    public function countPosts()
    {
        return count($this->posts);
    }
    public function addPost(IPost $post)
    {
        $this->posts[] = $post;
    }

    public function getSortedByCategory()
    {
        $arrSortedData = [];
        $arrComplectData = [];
        $categories = new Categories();
        foreach ($this->posts as $key => $post) {
            $categories->addCategory($post->getCategory());
            $arrComplectData[$post->getCategory()][] = $post;
        }
        $popular_categories = $categories->getMostPopular();

        $reverse_popular_categories = array_keys($popular_categories);
        foreach ($reverse_popular_categories as $val) {
            $arrSortedData = array_merge($arrSortedData, $arrComplectData[$val]);
        }

        return $arrSortedData;
    }
}
