<?php

namespace Norzechowicz\AceEditorBundle\Form\Extension\JsonEditor\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Norzechowicz\AceEditorBundle\Form\Extension\AceEditor\Type\AceEditorType;

final class JsonEditorType extends AbstractType
{
    public static $DEFAULT_UNIT = 'px';
    public static $UNITS = ['%', 'in', 'cm', 'mm', 'em', 'ex', 'pt', 'pc', 'px'];

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // Remove id from editor wrapper attributes. Id must be generated.
        $wrapperAttrNormalizer = function (Options $options, $jsonAttr) {
            if (is_array($jsonAttr)) {
                if (array_key_exists('id', $jsonAttr)) {
                    unset($jsonAttr['id']);
                }
            } else {
                $jsonAttr = [];
            }

            return $jsonAttr;
        };

        $defaultUnit = static::$DEFAULT_UNIT;
        $allowedUnits = static::$UNITS;
        $unitNormalizer = function (Options $options, $value) use ($defaultUnit, $allowedUnits) {
            if (is_array($value)) {
                return $value;
            }
            if (preg_match('/([0-9\.]+)\s*('.implode('|', $allowedUnits).')/', $value, $matchedValue)) {
                $value = $matchedValue[1];
                $unit = $matchedValue[2];
            } else {
                $unit = $defaultUnit;
            }

            return ['value' => $value, 'unit' => $unit];
        };

        $resolver->setDefaults([
            'required' => false,
            'wrapper_attr' => [],
            'width' => '100%',
            'height' => 250,
            'font_size' => 12,
            'mode' => 'tree',
        ]);

        $optionAllowedTypes = [
            'width' => ['integer', 'string', 'array'],
            'height' => ['integer', 'string', 'array'],
            'mode' => 'string',
        ];
        foreach ($optionAllowedTypes as $option => $allowedTypes) {
            $resolver->setAllowedTypes($option, $allowedTypes);
        }

        $optionNormalizer = [
            'wrapper_attr' => $wrapperAttrNormalizer,
            'width' => $unitNormalizer,
            'height' => $unitNormalizer,
        ];
        foreach ($optionNormalizer as $option => $normalizer) {
            $resolver->setNormalizer($option, $normalizer);
        }
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars = array_merge(
            $view->vars,
            [
                'wrapper_attr' => $options['wrapper_attr'],
                'width' => $options['width'],
                'height' => $options['height'],
                'font_size' => $options['font_size'],
                'mode' => $options['mode'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return AceEditorType::class;
    }
}
