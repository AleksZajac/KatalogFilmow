<?php
/**
 * UsersProfile Type.
 */
namespace App\Form;

use App\Entity\UsersData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
class UsersDataType extends AbstractType
{
    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            'login',
            TextType::class,
            [
                'required' => true,
                'attr' => ['max_length' => 255,
                    'placeholder' => 'Login'],
            ]
        );
        $builder->add(
            'name',
            TextType::class,
            [
                'required' => true,
                'attr' => ['max_length' => 255,
                    'placeholder' => 'Imię'],
            ]
        );
        $builder->add(
            'surname',
            TextType::class,
            [
                'required' => true,
                'attr' => ['max_length' => 255,
                    'placeholder' => 'Nazwisko'],
            ]
        );
    }
}