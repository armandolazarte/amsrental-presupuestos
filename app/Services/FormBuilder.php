<?php

/**
 * Description of FormMacros
 *
 * @author nadinarturo
 */

namespace App\Services;

class FormBuilder extends \Illuminate\Html\FormBuilder {

    public function bootstrap($name, $value, $label, $numCols=6, $editor = false, $type="text"){
        if($editor){
            $params['class'] = 'form-control ckeditor';
            $type = 'textarea';
        }else{
            $params['class'] = 'form-control';
        }
        $params['id'] = $name;
        $params['placeholder'] = $label;
        return view('templates.bootstrap.input_only', compact('name', 'value', 'label', 'numCols','type', 'params', 'editor'));
    }

    public function display($obj, $attrName, $numCols = 12) {
        $data['numCols'] = $numCols;
        $data['attrName'] = $attrName;
        $data['params']['id'] = str_replace('[]', '', $attrName);
        $data['attrValue'] = $obj->getValueAt($data['params']['id'], false);
        $data['params']['placeholder'] = $obj->getDescription($attrName);
        return \View::make('templates.bootstrap.display', $data);
    }

    public function btSelect($attrName, $values, $value, $numCols = 12, $required = true) {
        $data['numCols'] = $numCols;
        $data['attrName'] = $attrName;
        $data['params']['class'] = 'form-control';
        $data['attrValue'] = $value;
        $data['params']['id'] = $attrName;
        $data['options'] = $values;
        if ($required) {
            $data['params']['required'] = 'true';
        }
        $data['inputType'] = "select";
        return \View::make('templates.bootstrap.input', $data);
    }

    function simple2($object, $attrName, $numCols = 12, $type = 'text', $html = [], $options = []) {
        $this->model = $object;
        return $this->simple($attrName, $numCols, $type, $html, $options);
    }

    function simple($attrName, $numCols = 12, $type = 'text', $html = [], $options = []) {
        $obj = $this->model;
        $data['params'] = $html;
        if (!isset($data['params']['class'])) {
            $data['params']['class'] = '';
        }
        if ($obj->isDecimalField($attrName)) {
            $data['params']['class'] = 'decimal-format ';
        } else if ($obj->isRelatedField($attrName) && $type == 'text') {
            $type = 'select';
            $options = $obj->getRelatedOptions($attrName);
            if (count($options) > 30) {
                $data['params']['class'] = ' has-select2 ';
            }
        } else if ($obj->isDateField($attrName) && $type == "text") {
            $data['params']['class'] = 'jqueryDatePicker ';
            $data['attrValue'] = $obj->getValueAt($attrName);
            if (method_exists($data['attrValue'], 'format')) {
                $data['attrValue'] = $data['attrValue']->format('d/m/Y');
            }
        } else if ($obj->isBooleanField($attrName) && $type == "text") {
            $type = 'boolean';
        }
        $data['numCols'] = $numCols;
        $data['attrName'] = $attrName;
        if (!isset($data['attrValue'])) {
            $data['attrValue'] = $obj->getValueAt(str_replace('[]', '', $attrName), false);
        }
        $data['params']['id'] = str_replace('->', '_', str_replace('[]', '', $attrName));
        $data['params']['class'] .= 'form-control';
        $data['params']['placeholder'] = $obj->getDescription($attrName);
        if ($obj->isRequired($attrName)) {
            $data['params']['required'] = 'true';
        }
        $data['inputType'] = $type;
        $data['options'] = $options;
        if ($type == "multiselect") {
            unset($data['params']['required']);
            $data['params']['multiple'] = "";
            $data['params']['data-rel'] = "chosen";
            $arr = $obj->{$data['params']['id']};
            $data['attrValue'] = [];
            foreach ($arr as $value) {
                $data['attrValue'][] = $value->id;
            }
        }
        if ($type == "textarea") {
            $data['params']['rows'] = 4;
        }
        return \View::make('templates.bootstrap.input', $data);
    }

    function submitBt($btn_type = "btn-volver") {
        return \View::make('templates.bootstrap.submit', ['btn_type' => $btn_type]);
    }

}
