<?php

class Element
{
    private $tag;
    private $classes;
    public $children;
    public $attributes;
    public $style;
    public $inner_text;

    function __construct(string $tag) {
        $this->tag = $tag;
        $this->classes = array();
        $this->style = array();
        $this->children = array();
        $this->attributes = array();
        $this->inner_text = "";
    }

    public function get_html(): string {
        $html_struct = "<%s %s>%s</%s>";
        $attributes = "";
        $styles = "";
        $children = "";
        $classes = implode(" ", $this->classes);

        foreach ($this->children as $child) { $children .= $child->get_html(); }
        foreach ($this->attributes as $attribute => $value) { $attributes .= $attribute.'="'.$value.'" '; }
        foreach ($this->style as $style => $value) { $styles .= $style.":".$value.";"; }

        if ($styles != "") { $attributes .= 'style="'.$styles.'" '; }
        if ($classes != "") { $attributes .= 'class="'.$classes.'" '; }

        return sprintf($html_struct, $this->tag, $attributes, $this->inner_text.$children, $this->tag);
    }

    public function append_child(Element &$child): void {
        array_push($this->children, $child);
    }

    public function add_class(string $class_name): void {
        array_push($this->classes, $class_name);
    }

    public function remove_class(string $class_name): void {
        array_splice($this->classes, array_search($class_name), 1);
    }
}

class Dialog
{
    private $dialog;
    private $button_conatiner;
    public $header;
    public $paragraph;
    public $container;
    public $cancel;
    public $submit;

    function __construct(string $name, string $id) {
        $this->dialog = Document::create_element("div");
        $this->header = Document::create_element("h1");
        $this->container = Document::create_element("div");
        $this->button_conatiner = Document::create_element("div");
        $this->cancel = Document::create_element("button");
        $this->submit = Document::create_element("button");

        $this->dialog->append_child($this->header);
        $this->dialog->append_child($this->container);
        $this->dialog->append_child($this->button_conatiner);
        $this->button_conatiner->append_child($this->cancel);
        $this->button_conatiner->append_child($this->submit);

        $this->dialog->attributes["id"] = sprintf("dialog-%s-%s", $name, $id);
        $this->dialog->add_class($name);
        $this->button_conatiner->add_class("button-container");

        $this->submit->style["background-color"] = "var(--colour-green)";
        $this->cancel->attributes["onclick"] = "close_dialog();";

        $this->header->inner_text = sprintf("dialog-%s-%s", $name, $id);
        $this->cancel->inner_text = "Cancel";
        $this->submit->inner_text = "Submit";
    }

    public function get_html(): string {
        return $this->dialog->get_html();
    }
}

class Panel
{
    private $panel;
    private $label_container;
    public $header;
    public $action_button;
    public $description;

    function __construct(string $name, string $id) {
        $this->panel = Document::create_element("div");
        $this->header = Document::create_element("h4");
        $this->action_button = Document::create_element("button");
        $this->description = Document::create_element("p");
        $this->label_container = Document::create_element("div");

        $this->panel->append_child($this->header);
        $this->panel->append_child($this->description);
        $this->panel->append_child($this->label_container);

        $this->panel["id"] = $id;
        $this->header->inner_text = $name;
        $this->action_button->src = "/../.assets/icons/edit.svg";
    }

    public function add_label(string $value, string $src): Element {
        $label = Document::create_element("span");
        $label_image = Document::create_element("img");
        $label_value = Document::create_element("span");

        $this->label_container->append_child($label);
        $label->append_child($label_image);
        $label->append_child($label_value);

        $label_image["src"] = $src;
        $label_value->inner_text = $value;

        return $label;
    }

    public function get_html(): string {
        return $this->panel->get_html();
    }
}

class Document
{
    public static function create_element(string $tag): Element {
        return new Element($tag);
    }

    public static function create_dialog(string $name, string $id): Dialog {
        return new Dialog($name, $id);
    }

    public static function create_panel(string $name, string $id): Panel {
        return new Panel($name, $id);
    }

    /* TODO:
     *
     * [ ] get an element from already existing html code and be able to add children etc.
     *
     */
}

?>
