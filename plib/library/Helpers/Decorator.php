<?php  
/*
*************** 
* decorator class for multiple checboxes used int account creating and update
***************
*/
class Modules_Communigate_Helpers_Decorator extends Zend_Form_Decorator_Abstract
{


    protected $_beguin = '<div id="AccessModes-form-row" class="form-row">
                            <div class="field-name">
                                <label for="AccessModes">%s&nbsp;
                                <span class="required">*</span>
                                </label>
                            </div>
                            <div class="field-value">
                                    <table>
                                    <tbody>';
    protected $_elemtents = '              <td>
                                            <label for="%s-%s">
                                                <input type="checkbox" name="%s" id="%s-%s" value="%s" %s>
                                                %s
                                            </label>
                                            &nbsp
                                            </td>';
    protected $_end = '             </tbody>
                                </table>    
                               <span class="field-errors" style="display:none;"></span>
                            </div>
                            </div>
                            <br>';

    public function generateElements()
    {

        $element = $this->getElement();
        $values = $element->getValue();
        $accessModes = $element->options;
        $name    = htmlentities($element->getFullyQualifiedName());

        for ($i=0; $i < count($accessModes) ; $i++) { 
            $mode = array_keys($accessModes);
            $mode = $mode[$i];
            if (in_array($mode, $values) ) {
                $checked = "checked";
            } else {
                $checked = "";
            }
            $code[] = sprintf($this->_elemtents, $name, $mode, $name, $name, $mode, $mode, $checked,$mode);
        }
        return $code;
    }

    public function generateRows($pass)
    {

        $elements = $this->generateElements();

        switch ($pass) {
            case 0:
            $markup = '<tr>';
            for ($i=0; $i < 9 ; $i++) { 
                $markup .= $elements[$i];
            }
            $markup .= '</tr>';
            return $markup;
            break;
            case 1:
            $markup = '<tr>';
            for ($i=9; $i < 18 ; $i++) { 
                $markup .= $elements[$i];
            }
            $markup .= '</tr>';
            return $markup;
            case 2:
            $markup = '<tr>';
            for ($i=18; $i < 27 ; $i++) { 
                $markup .= $elements[$i];
            }
            $markup .= '</tr>';
            return $markup;
        }

    }

    public function generate()
    {

        $markup = '';
        $element = $this->getElement();
        $label = $element->getLabel();
        $markup .= sprintf($this->_beguin, $label);

        for ($i=0; $i < 3 ; $i++) { 
            $markup .= $this->generateRows($i);
        }

        $markup .= $this->_end;

        return $markup;
    }
    
    public function render($content)
    {
        $markup = $this->generate();
        return $markup;

    }
}


