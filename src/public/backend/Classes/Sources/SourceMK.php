<?

namespace App\Classes\Sources;

use App\Intefaces\IParserSource;

class SourceMK implements IParserSource
{

    private $target = "///item";
    private $sourcePath = "https://www.mk.ru/rss/index.xml";

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
