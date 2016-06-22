<?php


namespace Eccube\Form\Type\Front;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert; ★バリデーションを追加する際は、必ず必要となってきます。

class CrudType extends AbstractType
{
    public $config;

    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // 投稿種別の配列
        $post_type = array(
            '1' => '質問',
            '2' => '提案',
        );

        $builder->add( ★これ以降にバリデーションを追記
            'reason',
            'choice',
            array(
                'label' => '投稿種別',
                'required' => true, ★必須を有効に変更
                'choices' => $post_type,
                'mapped' => false,
                'expanded' => false,
                'multiple' => false,
            ),
        )
        ->add( ★ハンドルネームの項目追加
            'name',
            'text',
            array(
                'label' => '投稿者ハンドルネーム',
                'required' => true,
                'mapped' => false,
                 new Assert\Regex( ★正規表現でのバリデーション
                    array(
                        'pattern' => '/^[^\da-zA-Z]+$/u', ★条件
                        'message' => '半角英数字のみ入力可能です。', ★エラー時表示メッセージ
                    )
                )
            )
        )
        ->add(
            'title',
            'text',
            array(
                'label' => '投稿のタイトル',
                'required' => false,
                'mapped' => false,
                'constraints' => array(
                    new Assert\Length( ★文字入力の長さをチェック
                        array(
                            'min' => '0',
                            'max' => '100',
                            'maxMessage' => '100文字以内で入力してください',
                        )
                    )
                )
            )
        )
        ->add(
            'notes',
            'textarea',
            array(
                'label' => '内容',
                'required' => false,
                'mapped' => false,
                'empty_data' => null,
                'attr' => array(
                    'style' => 'height:100px;',
                ),
                'constraints' => array(
                    new Assert\Length( ★文字入力の長さをチェック
                        array(
                            'min' => '0',
                            'max' => '100',
                            'maxMessage' => '1000文字以内で入力してください',
                        )
                    )
                )
            )
        )
        ->addEventSubscriber(new \Eccube\Event\FormEventSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'crud';
    }
}
