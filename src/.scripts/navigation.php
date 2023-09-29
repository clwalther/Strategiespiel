<?php

class Navigation
{
    public static function construct_nav(string $path): void {
        $folders = explode("/", $path);
        $hyperlink = Document::create_element("a");
        $spacer = Document::create_element("i");

        $hyperlink->inner_text = "Strategiespiel";
        $spacer->inner_text = "/";
        $hyperlink->attributes["href"] = "/";

        echo $hyperlink->get_html().$spacer->get_html();

        for ($folder_index = 6; $folder_index < sizeof($folders); $folder_index++) {
            $hyperlink->inner_text = self::upper_case($folders[$folder_index]);
            $hyperlink->attributes["href"] .= $folders[$folder_index] . "/";

            echo $hyperlink->get_html().$spacer->get_html();
        }
    }

    public static function construct_drawer(string $path): void {
        $files = scandir($path);
        $button = Document::create_element("button");
        $image = Document::create_element("img");
        $span = Document::create_element("span");

        $button->append_child($image);
        $button->append_child($span);

        $image->attributes["src"] = "/.assets/icons/open_in_new.svg";

        foreach ($files as $filename) {
            if (!is_file($path . '/' . $filename) && substr($filename, 0, 1) != "." || $filename == "..") {
                $button->attributes["onclick"] = sprintf("window.open('./%s', '_self')", $filename);
                $span->inner_text = $filename == ".." ? $filename : "/".self::upper_case($filename);

                echo $button->get_html();
            }
        }
    }

    public static function construct_title(): void {
        $sub_paths = explode('/', $_SERVER[SCRIPT_NAME]);
        $curr_folder = $sub_paths[sizeof($sub_paths) - 2];

        if($curr_folder == '') {
            echo "Strategiespiel";
        } else {
            echo self::upper_case($curr_folder);
        }
    }

    private static function upper_case(string $name): string {
        $string_array = explode("-", $name);

        foreach ($string_array as &$string) {
            $string = ucfirst($string);
        }

        return implode("-", $string_array);
    }
}

?>
