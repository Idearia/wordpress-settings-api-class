# Come funziona

* Crea nuove pagine di opzioni in WordPress estendendo la classe `Idearia\SettingsPage`.
* Se vuoi creare un menu o sottomenu in WordPress senza opzioni, basta che estendi la classe `Idearia\MenuPage`.
* Accedi alle opzioni serializzate come fossi in Laravel estendend la classe `Idearia\Config`.

# Features

- Crea una pagina di opzioni usando solo array, niente codice richiesto.
- Usa il pratico template a tabs per raggruppare le opzioni.
- Controlla chi può vedere i menu.
- Controlla chi può modificare le opzioni.
- Controlla la posizione dei menu.
- Crea sia menu che sotto-menu.

# Dopo ogni modifica al repo...

Tagga una nuova versione, pushala su GitHub e aggiorna il repository Composer di Idearia:

```bash
git tag X.Y
git push origin master --tags
curl 'https://forge.laravel.com/servers/349942/sites/958281/deploy/http?token=CQtUNfSXr3t6aMLDsnvnF0NqjTrvmI2Voi2ZgPNY'
```


# Esempio pagina di menu semplice

```php
<?php

use Idearia\MenuPage;

class ExampleMenuPage extends MenuPage
{
    protected $label = 'Example Menu Page';

    protected $slug = 'example-menu-page';

    protected $capability = 'edit_posts';

    public function view()
    {
        echo '<h1>Hello world</h1>';
        echo '<p>';
        echo 'Questa pagina può essere vista solo dagli editor in su.';
        echo '</p>';
    }
}
```

# Esempio pagina dei settings completa

```php
<?php

use Idearia\SettingsPage;

class ExampleSettingsPage extends SettingsPage
{
    protected $label = 'Example Settings Page';

    protected $slug = 'example-settings-page';

    protected function getSections()
    {
        return array(
            array(
                'id'    => 'wedevs_basics',
                'title' => __( 'Basic Settings', 'wedevs' )
            ),
            array(
                'id'    => 'wedevs_advanced',
                'title' => __( 'Advanced Settings', 'wedevs' )
            )
        );
    }

    protected function getFields()
    {
        return array(
            'wedevs_basics' => array(
                array(
                    'name'              => 'text_val',
                    'label'             => __( 'Text Input', 'wedevs' ),
                    'desc'              => __( 'Text input description', 'wedevs' ),
                    'placeholder'       => __( 'Text Input placeholder', 'wedevs' ),
                    'type'              => 'text',
                    'default'           => 'Title',
                    'sanitize_callback' => 'sanitize_text_field'
                ),
                array(
                    'name'              => 'number_input',
                    'label'             => __( 'Number Input', 'wedevs' ),
                    'desc'              => __( 'Number field with validation callback `floatval`', 'wedevs' ),
                    'placeholder'       => __( '1.99', 'wedevs' ),
                    'min'               => 0,
                    'max'               => 100,
                    'step'              => '0.01',
                    'type'              => 'number',
                    'default'           => 'Title',
                    'sanitize_callback' => 'floatval'
                ),
                array(
                    'name'        => 'textarea',
                    'label'       => __( 'Textarea Input', 'wedevs' ),
                    'desc'        => __( 'Textarea description', 'wedevs' ),
                    'placeholder' => __( 'Textarea placeholder', 'wedevs' ),
                    'type'        => 'textarea'
                ),
                array(
                    'name'        => 'html',
                    'desc'        => __( 'HTML area description. You can use any <strong>bold</strong> or other HTML elements.', 'wedevs' ),
                    'type'        => 'html'
                ),
                array(
                    'name'  => 'checkbox',
                    'label' => __( 'Checkbox', 'wedevs' ),
                    'desc'  => __( 'Checkbox Label', 'wedevs' ),
                    'type'  => 'checkbox'
                ),
                array(
                    'name'    => 'radio',
                    'label'   => __( 'Radio Button', 'wedevs' ),
                    'desc'    => __( 'A radio button', 'wedevs' ),
                    'type'    => 'radio',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'selectbox',
                    'label'   => __( 'A Dropdown', 'wedevs' ),
                    'desc'    => __( 'Dropdown description', 'wedevs' ),
                    'type'    => 'select',
                    'default' => 'no',
                    'options' => array(
                        'yes' => 'Yes',
                        'no'  => 'No'
                    )
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'wedevs' ),
                    'desc'    => __( 'Password description', 'wedevs' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'file',
                    'label'   => __( 'File', 'wedevs' ),
                    'desc'    => __( 'File description', 'wedevs' ),
                    'type'    => 'file',
                    'default' => '',
                    'options' => array(
                        'button_label' => 'Choose Image'
                    )
                )
            ),
            'wedevs_advanced' => array(
                array(
                    'name'    => 'color',
                    'label'   => __( 'Color', 'wedevs' ),
                    'desc'    => __( 'Color description', 'wedevs' ),
                    'type'    => 'color',
                    'default' => ''
                ),
                array(
                    'name'    => 'password',
                    'label'   => __( 'Password', 'wedevs' ),
                    'desc'    => __( 'Password description', 'wedevs' ),
                    'type'    => 'password',
                    'default' => ''
                ),
                array(
                    'name'    => 'wysiwyg',
                    'label'   => __( 'Advanced Editor', 'wedevs' ),
                    'desc'    => __( 'WP_Editor description', 'wedevs' ),
                    'type'    => 'wysiwyg',
                    'default' => ''
                ),
                array(
                    'name'    => 'multicheck',
                    'label'   => __( 'Multile checkbox', 'wedevs' ),
                    'desc'    => __( 'Multi checkbox description', 'wedevs' ),
                    'type'    => 'multicheck',
                    'default' => array('one' => 'one', 'four' => 'four'),
                    'options' => array(
                        'one'   => 'One',
                        'two'   => 'Two',
                        'three' => 'Three',
                        'four'  => 'Four'
                    )
                ),
            )
        );
    }
}
```
