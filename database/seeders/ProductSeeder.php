<?php

namespace Database\Seeders;

use App\Models\AgeProduct;
use Carbon\Carbon;

use App\Models\Color;
use App\Models\ColorProduct;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $products = [
            [
                'name' => 'Plato manzanita',
                'slug' => Str::slug('Plato manzanita'),
                'price' => 49,
                // 'offer_price' => 30,
                // 'offer_date' => '2022-01-01',
                'subcategory_id' => 1,
                'variantes' => [
                    [
                        'color_id' => 1,
                        'quantity' => 150,
                        'qty-img' => 4
                    ],
                    [
                        'color_id' => 2,
                        'quantity' => 150,
                        'qty-img' => 4
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Plato de silicona de diseño único, material no tóxico, sin BPA, sin PVC, cuenta con bordes elevados, tamaños de porciones adecuados y succión fuerte para reducir derrames y desorden.</p><p>- Completamente resistente a las manchas.</p><p>- Para bebés a partir de los 12 meses.</p><p>Tamaño: (L) 18 x (A) 18 x (H) 3.8 cm. aprox.</p>'
            ],
            [
                'name' => 'Dinoplato',
                'slug' => Str::slug('Dinoplato'),
                'price' => 60,
                // 'offer_price' => 50,
                // 'offer_date' => '2022-01-10',
                'subcategory_id' => 2,
                'variantes' => [
                    [
                        'color_id' => 2, // verde
                        'quantity' => 100,
                        'qty-img' => 4
                    ],
                    [
                        'color_id' => 1,
                        'quantity' => 100,
                        'qty-img' => 4
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Plato de silicona de diseño único, material no tóxico, cuenta con bordes elevados, tamaños de porciones adecuadas.</p><p>- Succión fuerte para reducir derrames y desorden.</p><p>- Completamente resistente a las manchas, no tóxico, ecológico,</p><p>- Para bebés a partir de los 12 meses.</p><p>Tamaño: (L) 21,7 x (A) 16,7 x (H) 3.8 cm. aprox.</p><p>Tamaño de la cuchara: (L) 13,5 x (A) 2,8 cm aprox.</p>'
            ],
            [
                'name' => 'Babero con bolsillo - Elefante',
                'slug' => Str::slug('Babero con bolsillo - Elefante'),
                'price' => 33,
                'subcategory_id' => 3,
                'variantes' => [
                    [
                        'color_id' => 13,
                        'quantity' => 100,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Para bebés a partir de los 6 meses.</p><p>- Babero de silicona suave, cómodo y ecológico.</p><p>- De fácil limpieza: antibacteriano, lavable e impermeable.</p><p>- Ajustable (6 posiciones de ajuste).</p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames líquidos mientras los niños comen.</p><p>- Tamaño: 30 largo x 23 ancho aprox. (cm)</p>'
            ],
            [
                'name' => 'Babero con bolsillo - Ballena',
                'slug' => Str::slug('Babero con bolsillo - Ballena'),
                'price' => 33,
                'subcategory_id' => 3,
                'variantes' => [
                    [
                        'color_id' => 11,
                        'quantity' => 100,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Para bebés a partir de los 6 meses.</p><p>- Babero de silicona suave, cómodo y ecológico.</p><p>- De fácil limpieza: antibacteriano, lavable e impermeable.</p><p>- Ajustable (6 posiciones de ajuste).</p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames líquidos mientras los niños comen.</p><p>- Tamaño: 30 largo x 23 ancho aprox. (cm)</p>'
            ],
            [
                'name' => 'Babero con bolsillo - Dinosaurio',
                'slug' => Str::slug('Babero con bolsillo - Dinosaurio'),
                'price' => 33,
                'subcategory_id' => 3,
                'variantes' => [
                    [
                        'color_id' => 12,
                        'quantity' => 50,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Para bebés a partir de los 6 meses.</p><p>- Babero de silicona suave, cómodo y ecológico.</p><p>- De fácil limpieza: antibacteriano, lavable e impermeable.</p><p>- Ajustable (6 posiciones de ajuste).</p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames líquidos mientras los niños comen.</p><p>- Tamaño: 30 largo x 23 ancho aprox. (cm)</p>'
            ],
            [
                'name' => 'Babero con bolsillo - Perro',
                'slug' => Str::slug('Babero con bolsillo - Perro'),
                'price' => 33,
                'subcategory_id' => 3,
                'variantes' => [
                    [
                        'color_id' => 10,
                        'quantity' => 80,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Para bebés a partir de los 6 meses.</p><p>- Babero de silicona suave, cómodo y ecológico.</p><p>- De fácil limpieza: antibacteriano, lavable e impermeable.</p><p>- Ajustable (6 posiciones de ajuste).</p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames líquidos mientras los niños comen.</p><p>- Tamaño: 30 largo x 23 ancho aprox. (cm)</p>'
            ],
            [
                'name' => 'Babero con bolsillo - Cisne',
                'slug' => Str::slug('Babero con bolsillo - Cisne'),
                'price' => 33,
                'subcategory_id' => 3,
                'variantes' => [
                    [
                        'color_id' => 5,
                        'quantity' => 100,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Para bebés a partir de los 6 meses.</p><p>- Babero de silicona suave, cómodo y ecológico.</p><p>- De fácil limpieza: antibacteriano, lavable e impermeable.</p><p>- Ajustable (6 posiciones de ajuste).</p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames líquidos mientras los niños comen.</p><p>- Tamaño: 30 largo x 23 ancho aprox. (cm)</p>'
            ],
            [
                'name' => 'Babero con bolsillo - Buho',
                'slug' => Str::slug('Babero con bolsillo - Buho'),
                'price' => 33,
                'subcategory_id' => 3,
                'variantes' => [
                    [
                        'color_id' => 8,
                        'quantity' => 70,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Más detalles:</p><p>- Para bebés a partir de los 6 meses.</p><p>- Babero de silicona suave, cómodo y ecológico.</p><p>- De fácil limpieza: antibacteriano, lavable e impermeable.</p><p>- Ajustable (6 posiciones de ajuste).</p><p>- Tiene un receptor de alimentos que puede detener la comida o derrames líquidos mientras los niños comen.</p><p>- Tamaño: 30 largo x 23 ancho aprox. (cm)</p>'
            ],
            [
                'name' => 'Sonaja Loro',
                'slug' => Str::slug('Sonaja Loro'),
                'price' => 27,
                'subcategory_id' => 4,
                'video' => 'https://www.youtube.com/embed/EJMfj4bJT8E',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 18cm. x Ancho 7cm.</p>',
            ],
            [
                'name' => 'Sonaja Vaca',
                'slug' => Str::slug('Sonaja Vaca'),
                'price' => 27,
                'subcategory_id' => 4,
                'video' => 'https://www.youtube.com/embed/EJMfj4bJT8E',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 18cm. x Ancho 9cm.</p>',
            ],
            [
                'name' => 'Sonaja Perro',
                'slug' => Str::slug('Sonaja Perro'),
                'price' => 27,
                'subcategory_id' => 4,
                'video' => 'https://www.youtube.com/embed/EJMfj4bJT8E',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 18cm. x Ancho 10cm.</p>',
            ],
            [
                'name' => 'Sonaja Burro',
                'slug' => Str::slug('Sonaja Burro'),
                'price' => 27,
                'subcategory_id' => 4,
                'video' => 'https://www.youtube.com/embed/EJMfj4bJT8E',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 19cm. x Ancho 10cm.</p>',
            ],
            [
                'name' => 'Sonaja Submarino',
                'slug' => Str::slug('Sonaja Submarino'),
                'price' => 27,
                'subcategory_id' => 5,
                'video' => 'https://www.youtube.com/embed/9KlNpajoZSM',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 19cm. x Ancho 11cm.</p>',
            ],
            [
                'name' => 'Sonaja Barco',
                'slug' => Str::slug('Sonaja Barco'),
                'price' => 27,
                'subcategory_id' => 5,
                'video' => 'https://www.youtube.com/embed/9KlNpajoZSM',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 20cm. x Ancho 9.50cm.</p>',
            ],
            [
                'name' => 'Sonaja Avión',
                'slug' => Str::slug('Sonaja Avión'),
                'price' => 27,
                'subcategory_id' => 5,
                'video' => 'https://www.youtube.com/embed/9KlNpajoZSM',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 17cm. x Ancho 10cm.</p>',
            ],
            [
                'name' => 'Sonaja Auto',
                'slug' => Str::slug('Sonaja Auto'),
                'price' => 27,
                'subcategory_id' => 5,
                'video' => 'https://www.youtube.com/embed/9KlNpajoZSM',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 15.50cm. x Ancho 9.50cm.</p>',
            ],
            [
                'name' => 'Sonaja Cohete',
                'slug' => Str::slug('Sonaja Cohete'),
                'price' => 27,
                'subcategory_id' => 5,
                'video' => 'https://www.youtube.com/embed/9KlNpajoZSM',
                'brand_id' => 1,
                'quantity' => 300,
                'description' => '<p>Sonajeros diseños variados, para apaciguar al bebé, entrenamiento auditivo, entrenamiento de agarre.</p><p>Género: Unisex</p><p>Rango de edad: 0 a 24 meses</p><p>Tamaño del producto: Alto 16cm. x Ancho 13cm.</p>',
            ],
            // Subcategoria: Libro de tela sensorial - Marca: JollyBaby
            [
                'name' => 'Colitas de la granja',
                'slug' => Str::slug('Colitas de la granja'),
                'price' => 45,
                'subcategory_id' => 7,
                'brand_id' => 2,
                'quantity' => 300,
                'video' => 'https://www.youtube.com/embed/Djv1LCmggao',
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3
            ],
            [
                'name' => 'Colitas de la selva',
                'slug' => Str::slug('Colitas de la selva'),
                'price' => 45,
                'subcategory_id' => 7,
                'brand_id' => 2,
                'video' => 'https://www.youtube.com/embed/T3ASLpKwXtI',
                'quantity' => 300,
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3
            ],
            [
                'name' => 'Colitas curiosas',
                'slug' => Str::slug('Colitas curiosas'),
                'price' => 45,
                'subcategory_id' => 7,
                'brand_id' => 2,
                'quantity' => 300,
                'video' => 'https://www.youtube.com/embed/tZ9avMQGV5o',
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3,
            ],
            [
                'name' => 'Colitas frías',
                'slug' => Str::slug('Colitas frías'),
                'price' => 45,
                'subcategory_id' => 7,
                'brand_id' => 2,
                'video' => 'https://www.youtube.com/embed/SNAO-CYB_28',
                'quantity' => 300,
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3,
            ],
            [
                'name' => 'Colitas del mar',
                'slug' => Str::slug('Colitas del mar'),
                'price' => 45,
                'subcategory_id' => 7,
                'video' => 'https://www.youtube.com/embed/WdcsrZeuSR0',
                'brand_id' => 2,
                'quantity' => 150,
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3,
            ],
            [
                'name' => 'Colitas de dinosaurios',
                'slug' => Str::slug('Colitas de dinosaurios'),
                'price' => 45,
                'subcategory_id' => 7,
                'brand_id' => 2,
                'quantity' => 100,
                'video' => 'https://www.youtube.com/embed/NogOjL3RlRY',
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3,
            ],
            [
                'name' => 'Colitas de gatos',
                'slug' => Str::slug('Colitas de gatos'),
                'price' => 45,
                'subcategory_id' => 7,
                'brand_id' => 2,
                'quantity' => 50,
                'video' => 'https://www.youtube.com/embed/zskne_K-XUE',
                'description' => '<p>“Aprende mientras juega”.</p><p>Aumente la interacción entre padres e hijos y desarrolla las habilidades del idioma inglés, habilidades de comunicación, imaginación y sensoriales.</p><p><br></p><p>Más detalles:<br></p><p>- Libro de tela de educación temprana de colitas de animales</p><p>- Material: Poliéster, PET.</p><p>- Rango de edad: 0 a 24 meses.</p><p>- Colas de diferentes materiales, formas y colores</p><p>- Entrenamiento de audición, entrenamiento de agarre</p><p>- Al estrujar la primera y última página se produce un sonido crujiente</p><p>- Resistente al desgarro, costuras ajustadas y duraderas</p><p>- El color no se desvanece después del lavado, material seguro e inodoro.&nbsp;</p><p>- Tamaño: 22cm * 12cm * 4cm.</p>',
                'qty-img' => 3,
            ],
            // Subcategoria: Medias y Muñequeras - Marca: Sozzy
            [
                'name' => 'Cebra / Jirafa - Medias y muñequeras, Set 4pzas',
                'slug' => Str::slug('Cebra / Jirafa - Medias y muñequeras, Set 4pzas'),
                'price' => 40,
                'subcategory_id' => 6,
                'brand_id' => 3,
                'video' => 'https://www.youtube.com/embed/qjjtXHcw_G0',
                'quantity' => 300,
                'description' => '<p>¡Estimula los sentidos de tu bebé!&nbsp;</p><p>Set: 2 medias + 2 muñequeras</p><p>Colores vivos, atractivos, diferentes sonidos y texturas que ayudarán al desarrollo de la visión, oído y coordinación manos-pies.&nbsp;</p><p>Género: Unisex</p><p>Material: Tela suave</p><p>Rango de edad: 0-12 meses</p>',
                'qty-img' => 3
            ],

            // Subcategoria: Medias y Muñequeras - Marca: Happy Monkey
            [
                'name' => 'Oso / León - Medias y muñequeras, Set 4pzas',
                'slug' => Str::slug('Oso / León - Medias y muñequeras, Set 4pzas'),
                'price' => 35,
                'subcategory_id' => 6,
                'brand_id' => 4,
                'quantity' => 10,
                'description' => '<p>¡Estimula los sentidos de tu bebé!&nbsp;</p><p>Set: 2 medias + 2 muñequeras</p><p>Colores vivos, atractivos, diferentes sonidos y texturas que ayudarán al desarrollo de la visión, oído y coordinación manos-pies.&nbsp;</p><p>Género: Unisex</p><p>Material: Tela suave</p><p>Rango de edad: 0-12 meses</p>',
            ],

            // Subcategoria: Medias y Muñequeras - Marca: Sin Marca
            [
                'name' => 'Tigre / León- Muñequeras, Set 2pzas.',
                'slug' => Str::slug('Tigre / León- Muñequeras, Set 2pzas.'),
                'price' => 18,
                'subcategory_id' => 6,
                'quantity' => 6,
                'description' => '<p class="MsoNormal" style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">¡Estimula los sentidos de tu bebé! </span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></p><ul><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Set: 2 muñequeras.</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Colores vivos, atractivos, diferentes sonidos y
                                texturas que ayudarán al desarrollo de la coordinación manos- ojos. </span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Género: Unisex.</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Material: Tela suave.</span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Rango de edad: 0-12 meses.</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li></ul>',
                'qty-img' => 3
            ],

            // Subcategoria: Medias y Muñequeras - Marca: Sin Marca
            [
                'name' => 'Perro / Cerdito - Muñequeras, Set 2pzas.',
                'slug' => Str::slug('Perro / Cerdito - Muñequeras, Set 2pzas.'),
                'price' => 18,
                'subcategory_id' => 6,
                'quantity' => 6,
                'description' => '<p class="MsoNormal" style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">¡Estimula los sentidos de tu bebé!</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></p><ul><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Set: 2 muñequeras.</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Colores vivos, atractivos, diferentes sonidos y
                                texturas que ayudarán al desarrollo de la coordinación manos- ojos. </span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Género: Unisex.</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Material: Tela suave.</span></li><li style="margin-bottom: 0cm; line-height: normal; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><span style="font-size: 12pt; font-family: Arial, sans-serif; color: black; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;">Rango de edad: 0-12 mess.</span><span style="font-size: 12pt; font-family: Arial, sans-serif; background-image: initial; background-position: initial; background-size: initial; background-repeat: initial; background-attachment: initial; background-origin: initial; background-clip: initial;"><o:p></o:p></span></li></ul>',
                'qty-img' => 3
            ],
            [
                'name' => 'León Musical / Felpa',
                'slug' => Str::slug('León Musical / Felpa'),
                'price' => 45,
                'brand_id' => 2,
                'subcategory_id' => 8,
                'quantity' => 6,
                'description' => '<p>• Para edades de 0 meses a más.</p><p>• Superficie 100% lavable sin desvanecerse el color.</p><p>• Tela super suave y durable con ojos bordados, nariz y boca.</p><p>• Los colores brillantes estimulan la visión del bebé.</p><p>• Fácil de adjuntar con un clip multipropósito. Se conecta a un cochecito o una bolsa de pañales.</p><p>• Toca una melodía encantadora cuando la cola se tira, por ejemplo, twinkle pequeñas estrellas o es un mundo pequeño etc.</p>'
            ],
            [
                'name' => 'Rana Musical / Felpa ',
                'slug' => Str::slug('Rana Musical / Felpa '),
                'price' => 45,
                'brand_id' => 2,
                'subcategory_id' => 8,
                'quantity' => 6,
                'description' => '<p>• Para edades de 0 meses a más.</p><p>• Superficie 100% lavable sin desvanecerse el color.</p><p>• Tela super suave y durable con ojos bordados, nariz y boca.</p><p>• Los colores brillantes estimulan la visión del bebé.</p><p>• Fácil de adjuntar con un clip multipropósito. Se conecta a un cochecito o una bolsa de pañales.</p><p>• Toca una melodía encantadora cuando la cola se tira, por ejemplo, twinkle pequeñas estrellas o es un mundo pequeño etc.</p>'
            ],
            [
                'name' => 'Elefante de felpa - Multifuncional',
                'slug' => Str::slug('Elefante de felpa - Multifuncional'),
                'price' => 45,
                'brand_id' => 3,
                'subcategory_id' => 8,
                'quantity' => 6,
                'description' => '<p>• Edad aplicable: bebés (0-2 años).</p><p>• Material del juguete: Felpa de excelente calidad.</p><p>• Altura x Ancho: 18.50 cm x 20 cm.</p><p>• Juguete colgante ideal para coche, cuna, entre otros.</p><p>• Contribuyen de una manera muy positiva al desarrollo visual del bebé, a su percepción de los colores y a la coordinación ojo-mano. Además, en la parte de los pies tienen aros que suenan al chocar entre sí, favoreciendo a la función auditiva.</p>',
            ],
            [
                'name' => 'Panda de Felpa - Multifuncional',
                'slug' => Str::slug('Panda de Felpa - Multifuncional'),
                'price' => 40,
                'brand_id' => 2,
                'subcategory_id' => 8,
                'quantity' => 6,
                'description' => '<p><p>• Material: Felpa, relleno algodón suave, Libre de BPA.</p><p>• Dimensiones: alto 24cm. x ancho 16 cm.</p><p>• Para edades de 0 meses a más.</p><p>• Juguete colgante ideal para coche, cuna, entre otros.</p><p>• Contribuyen de una manera muy positiva al desarrollo visual del bebé, a su percepción de los colores y a la coordinación ojo-mano. Además, en la parte de los pies tienen aros que suenan al chocar entre sí, favoreciendo a la función auditiva.</p></p>'
            ],
            // [
            //     'name' => 'Producto prueba',
            //     'slug' => Str::slug('Producto prueba'),
            //     'price' => 1,
            //     'brand_id' => 2,
            //     'subcategory_id' => 8,
            //     'quantity' => 6,
            //     'description' => 'Su compra no será válida, por cualquier motivo, por favor no comprarla'
            // ],
            // Subcategoria: Mandil manga larga impermeable recreativo
            // - Marca: Sin Marca
            [
                'name' => 'Mandil Verde',
                'slug' => Str::slug('Mandil Verde'),
                'subcategory_id' => 9,
                'age_id' => 1,
                'video' => 'https://www.youtube.com/embed/l7N0KpPOu8M',
                'ages' => 2, // ambos
                'variantes' => [
                    [
                        'size_id' => 1,
                        'quantity' => 18,
                        'price' => 40,
                    ],
                    [
                        'size_id' => 2,
                        'quantity' => 18,
                        'price' => 45,
                    ],
                ],
                'qty-img' => 3,
                'description' => '<p>Recomendado para niños de 3 a 7 años. - Súper ligero, ideal en cualquier temporada. - Fresco- Fácil de limpiar y lavar- Secado rápido- Durable- El color permanece intacto después del lavado- No es tóxico.</p><p><br></p><p>Más detalles:</p><p>- Mandil manga larga impermeable.</p><p>- Tipo de tela: poliéster.&nbsp;</p><p>- Característica: Sin PVC, Sin BPA.</p><p>TALLA S: 90-110 cm. (niños de 3 a 5 años)</p><p>TALLA M: 110-130 cm. (niños de 5 a 7 años)</p>'
            ],
            [
                'name' => 'Mandil Azul',
                'slug' => Str::slug('Mandil Azul'),
                'subcategory_id' => 9,
                'qty-img' => 3,
                'video' => 'https://www.youtube.com/embed/l7N0KpPOu8M',
                'ages' => 2, // ambos
                'variantes' => [
                    [
                        'size_id' => 1,
                        'quantity' => 18,
                        'price' => 40,
                    ],
                    [
                        'size_id' => 2,
                        'quantity' => 18,
                        'price' => 45,
                    ],
                ],
                'description' => '<p>Recomendado para niños de 3 a 7 años. - Súper ligero, ideal en cualquier temporada. - Fresco- Fácil de limpiar y lavar- Secado rápido- Durable- El color permanece intacto después del lavado- No es tóxico.</p><p><br></p><p>Más detalles:</p><p>- Mandil manga larga impermeable.</p><p>- Tipo de tela: poliéster.&nbsp;</p><p>- Característica: Sin PVC, Sin BPA.</p><p>TALLA S: 90-110 cm. (niños de 3 a 5 años)</p><p>TALLA M: 110-130 cm. (niños de 5 a 7 años)</p>'
            ],
            [
                'name' => 'Mandil Rosado',
                'slug' => Str::slug('Mandil Rosado'),
                'subcategory_id' => 9,
                'qty-img' => 3,
                'video' => 'https://www.youtube.com/embed/l7N0KpPOu8M',
                'ages' => 2, // ambos
                'variantes' => [
                    [
                        'size_id' => 1,
                        'quantity' => 18,
                        'price' => 40,
                    ],
                    [
                        'size_id' => 2,
                        'quantity' => 18,
                        'price' => 45,
                    ],
                ],
                'description' => '<p>Recomendado para niños de 3 a 7 años. - Súper ligero, ideal en cualquier temporada. - Fresco- Fácil de limpiar y lavar- Secado rápido- Durable- El color permanece intacto después del lavado- No es tóxico.</p><p><br></p><p>Más detalles:</p><p>- Mandil manga larga impermeable.</p><p>- Tipo de tela: poliéster.&nbsp;</p><p>- Característica: Sin PVC, Sin BPA.</p><p>TALLA S: 90-110 cm. (niños de 3 a 5 años)</p><p>TALLA M: 110-130 cm. (niños de 5 a 7 años)</p>'
            ],
            [
                'name' => 'Beep Beep Stop',
                'slug' => Str::slug('Beep Beep Stop'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 12,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Pink Flowers',
                'slug' => Str::slug('Pink Flowers'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 13,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            // [
            //     'name' => 'Happy Cows',
            //     'slug' => Str::slug('Happy Cows'),
            //     'price' => 20,
            //     'subcategory_id' => 10,
            //     'age_id' => 1,
            //     'quantity' => 24,
            //     'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
            //     'qty-img' => 2
            // ],
            [
                'name' => 'Jungle Fever',
                'slug' => Str::slug('Jungle Fever'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 5,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Little Friends',
                'slug' => Str::slug('Little Friends'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 25,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Jirafa Mono Amarillo',
                'slug' => Str::slug('Jirafa Mono Amarillo'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 6,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'My name is Happy',
                'slug' => Str::slug('My name is Happy'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 22,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Childs Bear',
                'slug' => Str::slug('Childs Bear'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 16,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Happy Cows',
                'slug' => Str::slug('Happy Cows'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 16,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Smoking',
                'slug' => Str::slug('Smoking'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 18,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Mariquita',
                'slug' => Str::slug('Mariquita'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'variantes' => [
                    [
                        'color_id' => 6,
                        'quantity' => 18,
                        'qty-img' => 2
                    ],
                    [
                        'color_id' => 14,
                        'quantity' => 18,
                        'qty-img' => 2
                    ],
                ],
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
            ],
            [
                'name' => 'Whats Tea Under The Sea',
                'slug' => Str::slug('Whats Tea Under The Sea'),
                'price' => 20,
                'subcategory_id' => 10,
                'age_id' => 1,
                'quantity' => 18,
                'description' => '<p>Babero mandil&nbsp; súper práctico.</p><p>Material: EVA, saludable (No tóxico).</p><p>Cuello ajustable, manga con elástico</p><p>Impermeable</p><p>Cocido.</p><p>Excelente impresión.</p><p>Durables.</p><p>Fácil de lavar.</p><p>Fácil de poner y sacar.</p><p>Para peques desde los 9 meses hasta los 3 años</p><div><br></div>',
                'qty-img' => 2
            ],
            [
                'name' => 'Cadena Sujetador Azul',
                'slug' => Str::slug('Cadena Sujetador Azul'),
                'price' => 5,
                'subcategory_id' => 11,
                'quantity' => 50,
                'description' => '<p>Características: Sin PVC, Sin BPA.<br></p>'
            ],
            [
                'name' => 'Cadena Sujetador Amarillo',
                'slug' => Str::slug('Cadena Sujetador Amarillo'),
                'price' => 5,
                'subcategory_id' => 11,
                'quantity' => 50,
                'description' => '<p>Características: Sin PVC, Sin BPA.<br></p>'
            ],
            [
                'name' => 'Cadena Sujetador Rosado',
                'slug' => Str::slug('Cadena Sujetador Rosado'),
                'price' => 5,
                'subcategory_id' => 11,
                'quantity' => 50,
                'description' => '<p>Características: Sin PVC, Sin BPA.<br></p>'
            ],
            [
                'name' => 'Cadena Sujetador Verde',
                'slug' => Str::slug('Cadena Sujetador Verde'),
                'price' => 5,
                'subcategory_id' => 11,
                'quantity' => 50,
                'description' => '<p>Características: Sin PVC, Sin BPA.<br></p>'
            ],
            [
                'name' => 'Protectoras antideslizantes',
                'slug' => Str::slug('Protectoras antideslizantes'),
                'price' => 13,
                'subcategory_id' => 12,
                'quantity' => 50,
                'description' => '<p>Rodillera de gateo del bebé, antideslizantes, protectoras, calentador de rodillas pierna, resistentes, prácticas.</p><p>Seguridad para cuando nuestros bebés inician deslizándose sobre sus piernas o pancita o ir rodando por toda la habitación.</p><p><br></p><p>Tamaño: Estándar</p><p>Material: algodón</p><p>Edad&nbsp; &nbsp; &nbsp;: 5meses – 12 meses.&nbsp;</p><p>Paquete incluye: 1 par de medias para gateo</p><p>Disponible en negro.</p>'
            ],

        ];

        foreach ($products as $product) {
            $new_product = Product::create([
                'name'           => $product['name'],
                'slug'           => $product['slug'],
                'subcategory_id' => $product['subcategory_id'],
                'price'          => @$product['price'],
                'brand_id'       => @$product['brand_id'],
                'offer_price'    => @$product['offer_price'],
                'offer_date'     => @$product['offer_date'],
                'video'          => @$product['video'],
                'status'         => 2,
                'description'    => @$product['description']
            ])->orderBy('id', 'desc')->first();

            if (isset($product['ages'])) {
                for ($i = 0; $i < $product['ages']; $i++) {
                    AgeProduct::create([
                        'age_id' => $i + 1,
                        'product_id' => $new_product->id
                    ]);
                }
            }

            if (isset($product['qty-img']) && array_key_exists('variantes', $product)) {
                for ($i = 0; $i < $product['qty-img']; $i++) {
                    Image::create([
                        'url'            => 'products/' . $product['slug'] . '-' . ($i + 1) . '.jpg',
                        'imageable_id'   => $new_product->id,
                        'imageable_type' => Product::class
                    ]);
                }
            }

            if (array_key_exists('variantes', $product)) {
                foreach ($product['variantes'] as $key => $var) {
                    if (isset($var['color_id'])) {
                        $color_product = ColorProduct::create([
                            'color_id'   => $var['color_id'],
                            'slug'       => Str::slug($product['name'] . '-' . Color::find($var['color_id'])->name),
                            'quantity'   => $var['quantity'],
                            'product_id' => $new_product->id,
                        ])->orderBy('id', 'desc')->first();
                    } elseif (isset($var['size_id'])) {
                        $product_size = ProductSize::create([
                            'size_id'    => $var['size_id'],
                            'slug'       => Str::slug($product['name'] . '-' . Size::find($var['size_id'])->name),
                            'quantity'   => $var['quantity'],
                            'price'      => $var['price'],
                            'product_id' => $new_product->id,
                        ])->orderBy('id', 'desc')->first();
                    }
                    if (isset($var['qty-img'])) {
                        for ($i = 0; $i < $var['qty-img']; $i++) {
                            Image::create([
                                'url'            => 'products/' . $product['slug'] . '-' . $color_product->color->slug . '-' . ($i + 1) . '.jpg',
                                'imageable_id'   => $color_product->id,
                                'imageable_type' => ColorProduct::class
                            ]);
                        }
                    }
                }
            } else {
                $new_product->quantity = $product['quantity'];
                $new_product->save();

                if (isset($product['qty-img'])) {
                    for ($i = 0; $i < $product['qty-img']; $i++) {
                        Image::create([
                            'url' => 'products/' . $product['slug'] . '-' . ($i + 1) . '.jpg',
                            'imageable_id' => $new_product->id,
                            'imageable_type' => Product::class
                        ]);
                    }
                } else {
                    Image::create([
                        'url' => 'products/' . $product['slug'] . '.jpg',
                        'imageable_id' => $new_product->id,
                        'imageable_type' => Product::class
                    ]);
                }
            }
        }

        // Product::factory(250)->create()->each(function(Product $product) {
        //     Image::factory(4)->create([
        //         'imageable_id' => $product->id,
        //         'imageable_type' => Product::class
        //     ]);
        // });
    }
}
