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

    function __construct(string $name, int $id) {
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
        $this->cancel->inner_text = "Abbrechen";
        $this->submit->inner_text = "Bestätigen";
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

    function __construct(string $name, int $id) {
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

    public static function create_dialog(string $name, int $id): Dialog {
        return new Dialog($name, $id);
    }

    public static function create_panel(string $name, int $id): Panel {
        return new Panel($name, $id);
    }

    // specific and configured but standart elements
    public static function create_dialog_start_resume(): void {
        // create html elements
        $dialog = self::create_dialog("start-resume", 0);
        $paragraph = self::create_element("p");

        // append child
        $dialog->container->append_child($paragraph);

        // dialog conf
        $dialog->header->inner_text = "Starten oder Fortfahren";
        $dialog->submit->attributes["onclick"] = "close_dialog()";
        $dialog->submit->inner_text = "Start / Fortfahren";

        $paragraph->inner_text = "Start hintergrund Prozesse für Spiel. <caution>VORSICHT: Diese Aktion hat direkte und möglicherweise schwerwiegende Konsequenzen!</caution>";

        echo $dialog->get_html();
    }

    public static function create_dialog_stop_pause(): void {
        // create html elements
        $dialog = self::create_dialog("stop-pause", 0);
        $paragraph = self::create_element("p");

        // append child
        $dialog->container->append_child($paragraph);

        // dialog conf
        $dialog->header->inner_text = "Stoppen oder Pausieren";
        $dialog->submit->attributes["onclick"] = "close_dialog()";
        $dialog->submit->inner_text = "Stop / Pause";

        $paragraph->inner_text = "Stoppe hintergund Prozese.";

        echo $dialog->get_html();
    }

    public static function create_dialog_create_backup(): void {
        // create html elements
        $dialog = self::create_dialog("create-backup", 0);
        $paragraph = self::create_element("p");

        // append child
        $dialog->container->append_child($paragraph);

        // dialog conf
        $dialog->header->inner_text = "Backup";
        $dialog->submit->attributes["onclick"] = "close_dialog()";
        $dialog->submit->inner_text = "Backup";

        $paragraph->inner_text = "Mache eine Sicherheitskopie des momentanen Spielstandes.";

        echo $dialog->get_html();
    }

    public static function create_dialog_load_backup(): void {
        // create html elements
        $dialog = self::create_dialog("load-backup", 0);
        $ordered_list = self::create_element("ol");

        // append child
        $dialog->container->append_child($ordered_list);

        // dialog conf
        $dialog->header->inner_text = "Lade Backup";
        $dialog->submit->attributes["onclick"] = "close_dialog()";

        // create backup list
        $game_name = explode("/", $_SERVER['PHP_SELF'])[1];
        $conf_folder_path = "/var/www/html/Strategiespiel/conf.d/";
        $backup_folder_path = $conf_folder_path.$game_name."/backups.d/";

        foreach (scandir($backup_folder_path) as $content_name) {
            // check for content_name for being a valid backup folder
            // [ ] TODO: make better check for valid backup
            if ((!is_file($backup_folder_path.$content_name) && substr($content_name, 0, 1) != "." && substr($content_name, 0, 1) != "*")) {
                // create html elements for each list element
                $list_item = self::create_element("li");
                $button = self::create_element("button");
                $image = self::create_element("img");
                $span_date = self::create_element("span");
                $span_type = self::create_element("span");

                $ordered_list->append_child($list_item);
                $list_item->append_child($button);
                $button->append_child($image);
                $button->append_child($span_date);
                $button->append_child($span_type);

                // element conf
                $image->attributes["src"] = "/.assets/icons/shield.svg";
                $span_date->inner_text = explode(";", $content_name)[0];
                $span_type->inner_text = explode(";", $content_name)[1];
            }
        }

        echo $dialog->get_html();
    }

    /* TODO:
     *
     * [ ] get an element from already existing html code and be able to add children etc.
     *
     */
}

?>
