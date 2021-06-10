<?

namespace App\Classes\Sources;

use App\Intefaces\IParserSource;

class SourceLenta implements IParserSource
{

    private $target = "///item";
    private $sourcePath = "https://lenta.ru/rss/news/";

    public function getPath()
    {
        return $this->sourcePath;
    }
    public function setPath($path)
    {
        $this->sourcePath = $path;
    }

    public function getTarget()
    {
        return $this->target;
    }
    public function setTarget($target)
    {
        $this->target = $target;
    }
}
