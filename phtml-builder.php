<?php

/*
 * pHTML Builder
 * 
 * Copyright Ricard P. Barnes 2019.
 * https://github.com/ricardbarnes
 * 
 * Distributed under the MIT License.
 * https://opensource.org/licenses/MIT
 * 
 * For further information, please refer to the accompanying README.md file
 * 
 */

/**
 * Simple HTML builder.
 *
 * @author Ricard P. Barnes
 */
class Phtml {

    const CLOSED_TAG_LIST = [
        'br',
    ];
    const UNCLOSED_TAG_LIST = [
        '!DOCTYPE',
        'input',
        'link',
        'meta'
    ];
    const KEY_ONLY_ATTRS = [
        'html',
        'required'
    ];

    private $name;
    private $attributes;
    private $elements;    
    private $tag_open;
    private $tag_close;
    private $attrs_string;
    private $html_string;

    public function __construct() {
        $a = func_get_args();
        $i = func_num_args();
        if (method_exists($this, $f = '__construct' . $i)) {
            call_user_func_array(array($this, $f), $a);
        }
    }

    private function __construct0() {
        $this->init();
    }

    private function __construct1($tagName) {
        $this->name = $tagName;
        $this->init();
        if (in_array($tagName, Self::CLOSED_TAG_LIST)) {
            $this->tag_open = null;
            $this->tag_close = '<' . $tagName . '/>';
        } else if (in_array($tagName, self::UNCLOSED_TAG_LIST)) {
            $this->tag_open = '<' . $tagName . '>';
            $this->tag_close = null;
        } else {
            $this->tag_open = '<' . $tagName . '>';
            $this->tag_close = '</' . $tagName . '>';
        }
    }

    public function __toString() {
        try {
            if ($this->name === 'br') {
                return $this->tag_close;
            } else {
                $this->buildHTML();
                return $this->html_string;
            }
        } catch (Exception $ex) {
            return '<script>console.log(\'HTML Builder library says:\n ' . $ex . ' \')</script>';
        }
    }

    private function init() {
        $this->attributes = [];
        $this->elements = [];
        $this->attrs_string = null;
        $this->html_string = '';
    }

    private function buildHTML() {
        $this->html_string = '<' . $this->name;
        if (!empty($this->attributes)) {
            $this->buildAttributes();
            $this->html_string .= ' ' . $this->attrs_string;
        }
        if ($this->tag_open === null) {
            $this->html_string .= '/>';
        } else {
            $this->html_string .= '>';
            $this->buildElements();
            $this->html_string .= $this->tag_close;
        }
    }

    private function buildElements() {
        if (!empty($this->elements)) {
            foreach ($this->elements as $key => $element) {
                if ($key === 'text') {
                    $this->html_string .= $element;
                } else {
                    $this->html_string .= $element->__toString();
                }
            }
        }
    }

    private function buildAttributes() {
        $this->attrs_string = '';
        end($this->attributes);
        $last_key = key($this->attributes);
        foreach ($this->attributes as $key => $value) {
            if (in_array($key, Self::KEY_ONLY_ATTRS)) {
                $this->attrs_string .= $key;
            } else {
                $this->attrs_string .= $key . '="' . $value . '"';
            }
            if (strcmp($key, $last_key)) {
                $this->attrs_string .= ' ';
            }
        }
    }

    /**
     * If the attribute does exist, it will be replaced.
     * 
     * @param type $key
     * @param type $value
     */
    public function addAttribute($key, $value) {
        if (array_key_exists($key, $this->attributes)) {
            if ($value === false) {
                unset($this->attributes[$key]);
            }
        } else {
            if (in_array($key, self::KEY_ONLY_ATTRS)) {
                if ($value === true) {
                    $this->attributes[$key] = null;
                } else if ($value === false) {
                    unset($this->attributes[$key]);
                } else if ($value === null) {
                    $this->attributes[$key] = null;
                }
            } else {
                $this->attributes[$key] = $value;
            }
        }
    }

    /**
     * Elements will appear in the same order as they had been added.
     * 
     * @param type $element An object of this class
     */
    public function addElement($element) {
        array_push($this->elements, $element);
    }

    /**
     * Sets the inner content of the current element.
     * If there was any, it will be replaced.
     * 
     * @param type $text
     */
    public function setText($text) {
        $this->elements['text'] = $text;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->__construct1($name);
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function getElements() {
        return $this->elements;
    }

    public function setAttributes($attributes) {
        $this->attributes = $attributes;
    }

    public function setElements($elements) {
        $this->elements = $elements;
    }

}
