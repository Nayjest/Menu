<?php
namespace Nayjest\Menu;

use Config;
use HTML;
use Nayjest\Menu\Exception\NoConfigurationException;
use View;

class MenuSet
{
    protected $items;
    protected $template;

    public function __construct($config_key)
    {
        if (!Config::has($config_key)) {
            throw new NoConfigurationException("Menu '$config_key' has no configuration.");
        }
        $this->config_key = $config_key;

    }

    public function getOption($name, $default = null)
    {
        return Config::get("$this->config_key.$name", $default);
    }

    public function getTemplate()
    {
        if (!$this->template) {
            $this->template = $this->getOption('template', 'menu::default');
        }
        return $this->template;
    }

    public function getItems()
    {
        $items = $this->getOption('items', []);

        # if there is callback
        if (is_callable($items)) {
            $items = call_user_func($items);
        }
        $changes = false;

        # provide config keys to submenu
        foreach($items as $key => &$item) {
            if (isset($item['submenu'])) {
                if (empty($item['key'])) {
                    $item['key'] = "$this->config_key.items.$key";
                    $changes = true;
                }
                if (empty($item['template'])) {
                    $item['template'] = 'menu::submenu';
                    $changes = true;
                }
            }
        }
        $changes and $this->storeItems($items);
        return $items;
    }

    protected function storeItems($items)
    {
        Config::set("$this->config_key.items", $items);
    }

    public function render()
    {
        return View::make(
            $this->getTemplate(),
            [
                'menu' => $this,
            ]
        );
    }

    public function renderItem($item)
    {
        if (isset($item['submenu'])) {
            return new self($item['key']);
        }
        return call_user_func_array(['HTML','menuLink'], $item);
    }

    public function __toString()
    {
        return (string)$this->render();
    }

    public function setItem($key, $data = null)
    {
        $items = $this->getItems();
        if ($data) {
            $items[$key] = $data;
        } else {
            $items[] = ($data = $key);
        }
        $this->storeItems($items);
    }
} 