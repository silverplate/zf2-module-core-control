<?php

namespace CoreControl\Form\View\Helper;

use CoreControl\Form\Element\Files;
use Zend\Form\ElementInterface;

class MultiCheckbox extends \Zend\Form\View\Helper\FormMultiCheckbox
{
    public function render(ElementInterface $_element)
    {
        if ($_element instanceof Files) {
            return $this->_renderFiles($_element);
        }

        return parent::render($_element);
    }

    protected function _renderFiles(Files $_element)
    {
        $el = $_element;

        $name = $el->getName();

        $rendered = '
            <div class="form-control-static"><ul class="file">
                <li><label for="' . $name . '">Добавить</label></li>
                <li><input type="file" name="' . $name . '[]" id="' . $name . '" multiple></li>
            </ul></div>
        ';

        //echo $this->partial(
        //    'core-control/forms/client-file-size-checking',
        //    array('id' => $name)
        //);

        if ($el->getElements()) {
            $rendered .= '<hr>';
            $rendered .= '<ul class="element-files">';

            /** @var \Application\Entity\File $fileEntity */
            foreach ($el->getElements() as $fileEntity) {
                /** @var \Ext\Image $file */
                $file = $fileEntity->getFile();

                $rendered .= '<li>';

                if ($fileEntity->getTitle()) {
                    $rendered .= '<div class="wrap">' . $fileEntity->getTitle() . '</div>';
                }

                if ($file) {
                    $width = $height = null;

                    if ($fileEntity->isImage()) {
                        $maxWidth = 700;
                        $maxHeight = 90;

                        if ($file->getHeight() > $maxHeight) {
                            $height = $maxHeight;
                            $width = round($maxHeight / $file->getHeight() * $file->getWidth());

                        } else {
                            $height = $file->getHeight();
                            $width = $file->getWidth();
                        }

                        if ($width > $maxWidth) {
                            $width = $maxWidth;
                            $height = $maxWidth / $width * $height;
                        }
                    }

                    if ($fileEntity->getFilename()) {
                        $class = '';

                        if (
                            \Ext\String::getLength($fileEntity->getFilename()) > 25 &&
                            ($fileEntity->isImage() || $width < 250)
                        ) {
                            $class = ' class="wrap"';
                        }

                        $rendered .= sprintf(
                            '<div%s><a href="%s">%s</a></div>',
                            $class,
                            $fileEntity->getUri(),
                            $fileEntity->getFilename()
                        );
                    }

                    if ($fileEntity->isImage()) {
                        $rendered .= sprintf(
                            '<a href="%s"><img src="%s" width="%d" height="%d"></a>',
                            $fileEntity->getUri(),
                            $fileEntity->getUri(),
                            $width,
                            $height
                        );

                        $rendered .= '<br>';

//                        $rendered .= '<a href="' . $fileEntity->getCmsUri() . '">';
                        $rendered .= sprintf(
                            '%d&times;%d %s',
                            $file->getWidth(),
                            $file->getHeight(),
                            \Ext\String::toUpper($file->getExt())
                        );

//                    } else {
//                        $rendered .= '<a href="' . $fileEntity->getCmsUri() . '">';
                    }

                    $rendered .= $file->getSizeMeasure()['string'];
//                    $rendered .= '</a>';
                }

                $rendered .= '<div class="checkbox">';
                $rendered .= '<label><input type="checkbox" value="';
                $rendered .= $fileEntity->getId();

                if (strpos($name, ']') === false) {
                    $deleteName = $name . '-delete';
                } else {
                    $deleteName = preg_replace('/\]$/', '-delete]', $name);
                }

                $deleteName .= '[]';

                $rendered .= '" name="' . $deleteName . '">удалить</label>';
                $rendered .= '</div>';

                $rendered .= '</li>';
            }

            $rendered .= '</ul>';
            $rendered .= '<div class="clearfix"></div>';
        }

        return $rendered;
    }
}
