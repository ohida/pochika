<?php namespace Pochika\Entry;

use App;
use Conf;
use Event;
use Log;
use Markdown;
use PostRepository;
use Renderer;
use Yaml;

abstract class Entry implements \ArrayAccess {

    public $content;
    public $converted = false;
    public $published = false;

    public $key;
    public $path;
    public $data = [];

    public function __construct($file)
    {
        $this->path = $file instanceof \SplFileInfo ? $file->getPathname() : $file;

        $this->process();
    }

    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }

        #debug
        return null;

//        throw new \LogicException("Undefined property: Entry::[$name]");
    }

    /**
     * process
     *
     * @return void
     */
    protected function process()
    {
        $this->key = $this->makeKey();

        $this->read();

        $this->readFrontmatter();

        $this->checkPublishedFlag();
    }

    protected function makeKey()
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }

    /**
     * Read file
     * 
     * @return void
     */
    protected function read()
    {
        if (!file_exists($this->path)) {
            throw new \RuntimeException('file not exists: '.$this->path);
        }

        $this->content = file_get_contents($this->path);
    }

    /**
     * Read the YAML frontmatter.
     * 
     * @return void
     */
    protected function readFrontmatter()
    {
        $regex = '/^---\s*\n(.*?\n?)^---\s*$\n?(.*?)\n*\z/ms';
        if (preg_match($regex, $this->content, $m)) {
            $this->data = [];
            foreach (Yaml::parse($m[1]) as $key => $val) {
                $key = preg_replace('/-|\./', '_', $key);
                $this->data[$key] = $val;
            }
            $this->content = $m[2];
        } else {
            #debug
//          throw new \InvalidEntryException;
        }
    }

    protected function checkPublishedFlag()
    {
        if (!isset($this->data['published'])) {
            $this->published = true;
        } else {
            $this->published = bool($this->data['published']);
        }

        if (false === $this->published) {
            if ('local' != App::environment()) {
                throw new \InvalidEntryException;
            }
        }
    }

    /**
     * convert
     * 
     * @return void
     */
    public function convert()
    {
        if ($this->converted) {
            return;
        }

        Log::debug('post converted: '.$this->key);

        $event_params = (object)['entry' => &$this];

        Event::fire('entry.before_convert', $event_params);

        $this->content = Markdown::convert($this->content);

        $this->converted = true;

        Event::fire('entry.after_convert', $event_params);
    }

    /**
     * Render html
     *
     * @param array $payload
     * @return string
     * @codeCoverageIgnore
     */
    abstract function render($payload);

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->$key) || isset($this->data[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        return $this->$key;
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return void
     */
    public function offsetSet($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->data[$key]);
    }

}
