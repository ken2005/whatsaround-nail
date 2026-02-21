<?php

namespace Tests\Unit;

use App\Core\Dictionnaires;
use Tests\TestCase;

class DictionnairesTest extends TestCase
{
    public function test_get_mois_retourne_janvier_en_anglais(): void
    {
        $this->assertSame('january', Dictionnaires::getMois('janvier'));
    }

    public function test_get_mois_retourne_tous_les_mois_sans_cle(): void
    {
        $mois = Dictionnaires::getMois();
        $this->assertIsArray($mois);
        $this->assertArrayHasKey('janvier', $mois);
        $this->assertSame('december', $mois['décembre']);
    }

    public function test_get_mois_francais_retourne_les_cles(): void
    {
        $liste = Dictionnaires::getMoisFrancais();
        $this->assertContains('janvier', $liste);
        $this->assertContains('décembre', $liste);
        $this->assertCount(12, $liste);
    }

    public function test_get_jours_retourne_lundi_en_anglais(): void
    {
        $this->assertSame('monday', Dictionnaires::getJours('lundi'));
    }

    public function test_get_jours_francais_retourne_sept_jours(): void
    {
        $liste = Dictionnaires::getJoursFrancais();
        $this->assertContains('lundi', $liste);
        $this->assertContains('dimanche', $liste);
        $this->assertCount(7, $liste);
    }

    public function test_get_mois_cle_inconnue_retourne_null(): void
    {
        $this->assertNull(Dictionnaires::getMois('inconnu'));
    }
}
