<?php

use voku\helper\Phonetic;

/**
 * Class PhoneticAlgorithmsTest
 */
class PhoneticAlgorithmsTest extends \PHPUnit\Framework\TestCase
{
  public function testPhoneticMatches()
  {
    $phonetic = new Phonetic('de');
    $tests = [
        'Moelleken',  // '6546',
        'Mölleken',   // '6546',
        'Möleken',    // '6546',
        'Moeleken',   // '6546',
        'oder',       // '027',
        'was',        // '38',
        'Moellekenn', // '6546',
        'Moellecken', // '6546',
        'Möllecken',  // '6546',
        'Mölecken',   // '6546',
    ];

    if (\voku\helper\Bootup::is_php('7.0')) {
      $expected = [
          'Mölecken'   => 'Moelleken',
          'Moellecken' => 'Moelleken',
          'Möllecken'  => 'Moelleken',
          'Moellekenn' => 'Moelleken',
          'Moeleken'   => 'Moelleken',
          'Möleken'    => 'Moelleken',
          'Mölleken'   => 'Moelleken',
          'Moelleken'  => 'Moelleken',
      ];
    } else {
      $expected = [
          'Möllecken'  => 'Moelleken',
          'Moellekenn' => 'Moelleken',
          'Mölleken'   => 'Moelleken',
          'Moelleken'  => 'Moelleken',
          'Möleken'    => 'Moelleken',
          'Moellecken' => 'Moelleken',
          'Moeleken'   => 'Moelleken',
          'Mölecken'   => 'Moelleken',
      ];
    }

    self::assertSame(
        $expected,
        $phonetic->phonetic_matches('Moelleken', $tests)
    );
  }

  public function testPhoneticMatchesSentence()
  {
    $phonetic = new Phonetic('de');
    $tests = [
        123 => 'Ostern ganz fix: Schnelle Deko-Ideen selber machen',
        342 => 'Wäsche sortieren in 4 Schritten',
        621 => 'Saubere & glatte Wäsche dank Persil!',
        631 => 'Geld sparen beim Geschirrspülen in der Spülmaschine',
        311 => 'Sooo viel gratis: Die Henkel Familien-Aktion',
        312 => 'NEU: Der Sprühkopf für einfacheres Reinigen',
        313 => 'Das erste Mal: Wäsche färben',
        314 => 'Das erste Mal: Gardinen waschen',
        315 => 'Wäsche waschen & trocknen im Keller',
    ];

    if (\voku\helper\Bootup::is_php('7.0')) {
      $expected = [
          315 => [
              'Wasche'  => 'Wäsche',
              'troknen' => 'trocknen',
          ],
          621 => [
              'Wasche' => 'Wäsche',
          ],
          313 => [
              'Wasche' => 'Wäsche',
          ],
          342 => [
              'Wasche' => 'Wäsche',
          ],
      ];
    } else {
      $expected = [
          315 => [
              'Wasche'  => 'Wäsche',
              'troknen' => 'trocknen',
          ],
          342 => [
              'Wasche' => 'Wäsche',
          ],
          313 => [
              'Wasche' => 'Wäsche',
          ],
          621 => [
              'Wasche' => 'Wäsche',
          ],
      ];
    }

    self::assertSame(
        $expected,
        $phonetic->phonetic_matches('Wasche troknen? Wie geht das?', $tests)
    );
  }

  public function testPhoneticSentence()
  {
    $phonetic = new Phonetic('de');
    self::assertSame(
        ['Moelleken' => '6546'],
        $phonetic->phonetic_sentence('Moelleken oder was?')
    );

    self::assertSame(
        [
            'Moelleken' => '6546',
            'oder'      => '027',
            'was'       => '38',
        ],
        $phonetic->phonetic_sentence('Moelleken oder was?', false)
    );

    self::assertSame(
        [
            'Moelleken' => '6546',
        ],
        $phonetic->phonetic_sentence('Moelleken oder was?', true)
    );

    self::assertSame(
        [
            'Moelleken' => '6546',
        ],
        $phonetic->phonetic_sentence('Moelleken oder was?', false, 4)
    );
  }

  public function testPhoneticWord()
  {
    $phonetic = new Phonetic('de');
    self::assertSame('6546', $phonetic->phonetic_word('Moelleken'));
  }
}
