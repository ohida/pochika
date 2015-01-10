<?php namespace Pochika\Entry;

use App\Events\AfterConvert;
use Event;
use Log;
use Markdown;
use Yaml;

abstract class Entry implements \ArrayAccess {

    protected $meta;

    public $key;
    public $content;
    public $date;
    public $path;
    public $published = false;
    public $converted = false;

    abstract public function render($payload);

    public function __construct($file)
    {
        $this->path = $file instanceof \SplFileInfo ? $file->getPathname() : $file;
        $this->process();
    }

    public function __get($key)
    {
        if (isset($this->{$key})) {
            return $this->{$key};
        } elseif (isset($this->meta[$key])) {
            return $this->meta[$key];
        }
        //throw new \ErrorException('unknown property: '.$key);
    }

    /**
     * process
     *
     * @return void
     */
    protected function process()
    {
        $this->key = $this->key();
        $this->readData();
        $this->checkPublishedFlag();
    }

    protected function key()
    {
        return pathinfo($this->path, PATHINFO_FILENAME);
    }

    /**
     * Read file
     * 
     * @return void
     */
    protected function readData()
    {
        if (!is_readable($this->path)) {
            throw new \RuntimeException('cannot read file: '.$this->path);
        }

        $buff = file_get_contents($this->path);
        $this->readFrontmatter($buff);
    }

    /**
     * Read frontmatter and content
     * 
     * @return void
     */
    protected function readFrontmatter($buff)
    {
        $regex = '/^---\s*\n(.*?\n?)^---\s*$\n?(.*?)\n*\z/ms';
        $this->meta = [];
        if (preg_match($regex, $buff, $m)) {
            foreach (Yaml::parse($m[1]) as $key => $val) {
                $key = preg_replace('/-|\./', '_', $key);
                $this->meta[$key] = $val;
            }
            $this->content = $m[2];
            $this->parseDate();
        } else {
            $this->content = $buff;
            //throw new \InvalidEntryException;
        }
    }

    protected function parseDate()
    {
        if (($date = element('date', $this->meta))) {
            $this->date = strtotime($date);
            unset($this->meta['date']);
        }
    }

    protected function checkPublishedFlag()
    {
        if (!isset($this->meta['published'])) {
            $this->published = true;
        } else {
            $this->published = bool($this->meta['published']);
        }

        if (false === $this->published) {
            if ('local' != env('APP_ENV')) {
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

        start_measure('convert:'.$this->key);

        //Event::fire(new BeforeConvert($this));

        $this->content = Markdown::parse($this->content);
        $this->converted = true;

        Event::fire(new AfterConvert($this));

        stop_measure('convert:'.$this->key);
        Log::debug('entry converted: '.$this->key);
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key)
    {
        return isset($this->{$key}) || isset($this->meta[$key]);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function offsetGet($key)
    {
        if (isset($this->{$key})) {
            return $this->{$key};
        } elseif (isset($this->meta[$key])) {
            return $this->meta[$key];
        }
        
        return null;
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
        $this->meta[$key] = $value;
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  string  $key
     * @return void
     */
    public function offsetUnset($key)
    {
        unset($this->meta[$key]);
    }

}
