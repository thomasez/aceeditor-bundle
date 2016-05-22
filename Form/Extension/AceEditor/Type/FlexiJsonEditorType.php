<?php

/**
 * This file is part of the AceEditorBundle.
 *
 * (c) Norbert Orzechowicz <norbert@orzechowicz.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Norzechowicz\AceEditorBundle\Form\Extension\AceEditor\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AceEditorType
 *
 * @author Norbert Orzechowicz <norbert@orzechowicz.pl>
 */
class FlexiJsonEditorType extends AbstractType
{
    private $defaultUnit = 'px';
    private $units = array('%', 'in', 'cm', 'mm', 'em', 'ex', 'pt', 'pc', 'px');

    /**
     * Add the image_path option
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolverInterface $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        // Remove id from ace editor wrapper attributes. Id must be generated.
        $wrapperAttrNormalizer = function (Options $options, $aceAttr) {
            if (is_array($aceAttr)) {
                if (array_key_exists('id', $aceAttr)) {
                    unset($aceAttr['id']);
                }
            } else {
                $aceAttr = array();
            }

            return $aceAttr;
        };

        $defaultUnit = $this->defaultUnit;
        $allowedUnits = $this->units;
        $unitNormalizer = function(Options $options, $value) use ($defaultUnit, $allowedUnits) {
            if(is_array($value)) {
                return $value;
            }
            if(preg_match('/([0-9\.]+)\s*(' . join('|', $allowedUnits) . ')/', $value, $matchedValue)) {
                $value = $matchedValue[1];
                $unit = $matchedValue[2];
            } else {
                $unit = $defaultUnit;
            }
            return array('value' => $value, 'unit' => $unit);
        };
        $resolver->setDefaults(array(
            'required' => false,
            'flexijsoneditor_wrapper_attr' => array(),
            'width' => 200,
            'height' => 200,
            'font_size' => 12,
        ));

        $resolver->setAllowedTypes('width', array('integer', 'string', 'array'));
        $resolver->setAllowedTypes('height', array('integer', 'string', 'array'));
        $resolver->setAllowedTypes('font_size', 'integer');

        $resolver->setNormalizer('flexijsoneditor_wrapper_attr', $wrapperAttrNormalizer);
        $resolver->setNormalizer('width' , $unitNormalizer);
        $resolver->setNormalizer('height', $unitNormalizer);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge(
            $view->vars,
            array(
                'flexijsoneditor_wrapper_attr' => $options['flexijsoneditor_wrapper_attr'],
                'width' => $options['width'],
                'height' => $options['height'],
                'font_size' => $options['font_size'],
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\TextareaType::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'flexijsoneditor';
    }
}
