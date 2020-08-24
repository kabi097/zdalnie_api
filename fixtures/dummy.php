<?php

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Reply;
use App\Entity\User;

function getPost($number) {
  $title = [
    'Zlecę wykonanie szablonu do Wordpressa',                          
    'Korepetycje z matematyki - równania nieliniowe',                   
    'Zlecę przetłumaczenie artykułu na język francuski',
    'Poszukuję pomocy w obsłudze księgowej małej firmy',
    'Pomoc przy montażu podcastów',
    'Poszukuje firmy która poprowadzi sprzedaż na Allegro',
    'Napisanie 10 artykułów w języku angielskim na temat żywienia',
    'Potrzebuję porady prawnej dotyczącej spadków',
    'Zlecę wykonanie transkrypcji nagrań video',
    'Zaprojektowanie aplikacji mobilnej dla systemu Android',
    'Poszukuję zdalnych korepetycji z informatyki (teoria baz danych)',
    'Zlecę spolszczenie projektów ulotki',
    'Szukam dobrego doradcy podatkowego',
    'Nagranie muzyki do filmu reklamowego',
    'Zlecę stworzenie arkusza w programie Excel.',
    'Zlecę napisanie 3 felietonów na temat szkolnictwa',
    'Pomoc w wyborze sprzętu komputerowego',
    'Szukam klientów dla agencji reklamowej',
    'Optymalizacja bazy danych PostgreSQL',
    'Pomoc przy opracowaniu pracy inżynierskiej',
    'Przetłumaczenie serii filmów na YouTube',
    'Zlecę analizę księgową firmy',
    'Mastering i mix utworów na wesele',
    'Szukam eksperta ds. sprzedaży (stałe zlecenia)',
    'Zlecę napisanie powieści kryminalnej',
    'Szukam pomocy w zakresie ogrodnictwa',
    'Zlecę opracowanie scenariusza filmowego',
    'Przepisanie szablonu strony z Adobe XD do CSS',
    'Pomoc w rozwiązaniu karty zadań z geografii',
    'Potrzebuję pomocy w odszyfrowaniu chińskiego',
    'Pomoc w obsłudze programu iFirma',
    'Wgranie soundtracków do filmu promocyjnego',
    'Obsługa sklepu internetowego PrestaShop',
    'Szukam copywritera do tekstów reklamowych',
    'Szukam pomocy przy budowie domu (projekt)',
    'Wysyłka 1000 maili o różnej treści'
  ];
  return $title[$number];
}

return [
  Category::class => [
    'category_0' => [
        'name' => 'Programowanie',
        'icon' => 'mdi-laptop',
      ],

    'category_1' => [
        'name' => 'Nauka i korepetycje',
        'icon' => 'mdi-flask',
      ],
      
    'category_2' => [
        'name' => 'Tłumaczenia',
        'icon' => 'mdi-translate',
      ],

    'category_3' => [
        'name' => 'Księgowość',
        'icon' => 'mdi-domain',
      ],

    'category_4' => [
        'name' => 'Dźwięk i muzyka',
        'icon' => 'mdi-music',
      ],

    'category_5' => [
        'name' => 'Firma',
        'icon' => 'mdi-file-document',
      ],

    'category_6' => [
        'name' => 'Copywriting',
        'icon' => 'mdi-book',
      ],

    'category_7' => [
        'name' => 'Porady',
        'icon' => 'mdi-lightbulb',
      ],

    'category_8' => [
        'name' => 'Pozostałe',
        'icon' => 'mdi-remote',
      ],
    ],

    User::class => [
      'user_{1..20}' => [
          'email' => '<email()>',
          'password'=> '\$argon2i\$v=19\$m=65536,t=4,p=1\$ZnNyU2tVSkdMZ2lkMFNKTg\$NaGbeHzrKnceX/93LCx9ye8c0ry099dXpZMkV3aDeEw',
          'username'=> '<firstName()> <lastName()>',
          'publicPhone'=> '<phoneNumber()>',
          'publicAddress'=> '<streetAddress()>',
          'type'=> '<boolean()>',
          'description'=> '<text()>',
          'website'=> '<url()>',
          'github'=> 'https://github.com/kabi097',
        ]
    ],

  Post::class => [
      'post_{0..35}' => [
          'title' => '<getPost($current)>',
          'description' => '<text()>',
          'budget' => '<numberBetween(100, 1000)>',
          'days' => '<numberBetween(7, 45)>',
          'category' => '@category_<($current%9)>',
          'user' => '@user_<numberBetween(1,10)>',
      ],
    ],
    Reply::class => [
      'reply_{1..70}' => [
        'user' => '@user_<numberBetween(11,20)>',
        'content' => '<text()>',
        'price' => '<numberBetween(100,1000)>',
        'type' => 'całość',
        'isPublished' => true,
        'post' => '@post_<numberBetween(0,35)>',
      ]
    ]
];