<?

namespace App\Classes\Parser;

use App\Classes\Posts\PostCollection;
use App\Classes\Posts\Post;
use App\Intefaces\IParser;



class ParserReaderXML extends Parser implements IParser
{

    private $parser,
        $error_code,
        $error_string,
        $current_line,
        $current_column,
        $data = array(),
        $datas = array();

    protected function parse($data)
    {
        $this->parser = xml_parser_create('UTF-8');
        xml_set_object($this->parser, $this);
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
        xml_set_element_handler($this->parser, 'tag_open', 'tag_close');
        xml_set_character_data_handler($this->parser, 'cdata');
        if (!xml_parse($this->parser, $data)) {
            $this->data = array();
            $this->error_code = xml_get_error_code($this->parser);
            $this->error_string = xml_error_string($this->error_code);
            $this->current_line = xml_get_current_line_number($this->parser);
            $this->current_column = xml_get_current_column_number($this->parser);
        } else {
            $this->data = $this->data['child'];
        }
        xml_parser_free($this->parser);
    }
    protected function getAttributeData($massive, $img = false)
    {
        if ($img == false) {
            return $massive[0]['data'];
        } else {
            return $massive[0]['attribs']['URL'];
        }
    }

    private function tag_open($parser, $tag, $attribs)
    {
        $this->data['child'][$tag][] = array('data' => '', 'attribs' => $attribs, 'child' => array());
        $this->datas[] = &$this->data;
        $this->data = &$this->data['child'][$tag][count($this->data['child'][$tag]) - 1];
    }

    private function cdata($parser, $cdata)
    {
        $this->data['data'] .= $cdata;
    }

    private function tag_close($parser, $tag)
    {
        $this->data = &$this->datas[count($this->datas) - 1];
        array_pop($this->datas);
    }

    private function loadData()
    {
        return file_get_contents($this->getSourcePath());
    }
    public function getRawData()
    {
        return $this->loadData();
    }

    public function getData()
    {

        $xml = $this->getRawData();

        $this->parse($xml);

        $this->postCollection = new PostCollection();
        foreach ($this->data['RSS'] as $key => $rss) {
            foreach ($rss['child']['CHANNEL'] as $key1 => $channel) {
                foreach ($channel['child']['ITEM'] as $key2 => $item) {
                    $post = new Post();
                    $post->setTitle((string)$this->getAttributeData($item['child']['TITLE']));
                    $post->setCategory((string)$this->getAttributeData($item['child']['CATEGORY']));
                    $post->setDescription((string)$this->getAttributeData($item['child']['DESCRIPTION']));
                    $post->setDatePublish((string)$this->getAttributeData($item['child']['PUBDATE']));
                    $post->setPreviewPhotoPath(($item['child']['ENCLOSURE']) ? (string)$this->getAttributeData($item['child']['ENCLOSURE'], true) : $post->getPreviewPhotoPath());
                    $this->postCollection->addPost($post);
                }
            }
        }
        return $this->postCollection;
    }
}
