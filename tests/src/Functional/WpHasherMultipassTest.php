<?php
/*
 * This file is part of the gm-wp-hasher package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gm\WpHasher\Tests\Functional;

use Gm\WpHasher\WpHasher;


/**
 * @author  Giuseppe Mazzapica <giuseppe.mazzapica@gmail.com>
 * @license https://creativecommons.org/publicdomain/zero/1.0/ Creative Commons Zero v1.0 Universal
 * @package gm-wp-hasher
 */
class WpHasherMultipassTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider providePasswordSamples
     * @param string $plain
     * @param string $hashed
     */
    public function testCheckPasses($plain, $hashed)
    {
        $hasher = new WpHasher();
        $this->assertTrue($hasher->check($plain, $hashed));
    }

    /**
     * @dataProvider providePasswordSamples
     * @param string $plain
     * @param string $hashed
     */
    public function testCheckDoNotPassesIfPlainIsAltered($plain, $hashed)
    {
        $hasher = new WpHasher();

        $plain = substr($plain, rand(1, 7));

        $this->assertFalse($hasher->check($plain, $hashed));
    }

    /**
     * @dataProvider providePasswordSamples
     * @param string $plain
     * @param string $hashed
     */
    public function testCheckDoNotPassesIfHashIsAltered($plain, $hashed)
    {
        $hasher = new WpHasher();

        $hashed = substr($hashed, rand(1, 7));

        $this->assertFalse($hasher->check($plain, $hashed));
    }

    /**
     * @dataProvider providePasswordSamples
     * @param string $plain
     * @param string $hashed
     */
    public function testHashAndCheck($plain, $hashed)
    {
        $hasher = new WpHasher();
        $newHash = $hasher->hash($plain);
        $this->assertTrue($hasher->check($plain, $newHash));
    }

    /**
     * Returns an array of arrays, where each element is an array where 1st item is
     * the plain text password and the 2nd element is related encoded password.
     *
     * The pain/hashed password couples were generated in WordPress context using:
     *
     * <code>
     * $plain  = wp_generate_password(mt_rand(8, 16), mt_rand(1, 2) > 1.5);
     * $hashed = wp_hash_password($plain);
     * </code>
     *
     * @return array
     */
    public function providePasswordSamples()
    {
        return [
            ['s@tWx2$b', '$P$BjM90FTBvgdVXgJ/uDrJoHEvI4goc./'],
            ['q9c)@SSw#%HjSxe', '$P$BifmdM/CjulzFVpK0Gj4xUxgQpgsWu.'],
            ['ks0YA8BWapTg', '$P$BbGaJPArkfzfVidn5PTKehtkpzErDE1'],
            ['@e4S28O4zPf@AKh!', '$P$B587gTybpmg6Hu4qHpkUYhrphtO.RQ1'],
            ['u%Vzhv@P', '$P$BgNA2DBhVwQc4Mt0MidKTL/VbCfmyE/'],
            ['1qjeLVpNsCpe', '$P$B7gS/7KHXN4/t55D56moJ73G1IQItQ0'],
            ['UL(fjeD)PJn', '$P$BFGWYCfs7CAJQL8UPxptyQ5jVUe3XQ0'],
            ['(oEDU^isv1', '$P$BGamVLL9CYcpmhZ9XXMJACiZuV32qU.'],
            ['KMyuLsK3v5', '$P$BcxO3TBgepfNLJTi.orFGKJ7wD8jns1'],
            ['ksTcmIFjHU7hc', '$P$BEBqy9memLSc0MvsQKOVIuopf2l4PH.'],
            ['lwsupUwr', '$P$Bb5T9IcIrx0R5GBhBV9rDvinUYfR.W1'],
            ['IUMOsJgPKR', '$P$B34JPLC9UJS09lUsm2YckUSvtqQ4/R0'],
            ['ydSKtDaBsWoG', '$P$Bjir625US1zifti6QINgzqcMoaZob61'],
            ['^0xTpg^0I', '$P$B.UMN6owS3JdUYawwBv9lFWeTnwjAq1'],
            ['r4$^QB4g0K@80Ew', '$P$BNWq8DFdTcer6c.gHOlNaxuLQrf/WL0'],
            ['cFUp4U7jFD', '$P$BVi9dbRtrHebdY7yC8tFxBpaeRzoDl.'],
            ['mL9ORD60wOCoaJ6', '$P$BlI5zm2UCNF2WK8aBP5r78Q8mdgYIm/'],
            ['Po6QAQ67JS7qV', '$P$By1IwfzWWk8TN.UsoUdkrqjV.VhrFO1'],
            ['xvchEpU9S7bu', '$P$B57UIeXWkXGGBmLVannve9L1wIp2Vh0'],
            ['HSteuRudYvo&&', '$P$BEJZ.AP6HlH66bp1CLPQlw0dH/gdo4.'],
            ['!(E^RhUX@99H25M', '$P$BPGv9xASWEWW8RZ7XceP4yCJM1RUhd0'],
            ['&Og^0s$KG!q)N', '$P$BX/nBDDoOG.3rfHjjsYXyGP2/J6Ooz1'],
            ['xfpNwFRgQ', '$P$BsftNU0MIwGO/CYix57/8EVRRvb5u8/'],
            ['wHrUK7yQpsDDgsY', '$P$BslpFXG/Ew7tP9RHFdOHDFPbUUBYcL/'],
            ['Fjg^znXwC*A*', '$P$BHZ7A.26FWcYuyKXbZFkz60mTX8LvN0'],
            ['Uvv1dyu7', '$P$BTZQSDVMQuJuM4pCipQLYCuIh3vied1'],
            ['YVGNwD6VzLtUccx', '$P$Be9oDkBF.4uqG1SeLa6dj8MoQ.JUIU.'],
            ['Bpe8KPNW', '$P$B7HeMPwOE9rTbK4wprwRevB8Y.GFlW1'],
            ['*8XaeBb8KUBV', '$P$BJOOz3pEzUBL6nKOHCnSkRP7xzgzaM.'],
            ['9VSLJYMuC6h6', '$P$BGEpbq.tLiCxt8UxKRz/VKO0qNtFq6.'],
            ['eqXV(OJWe3Z9u7B1', '$P$B3GBeQaFnBw0AiP5BKe5M6h5ACC52T0'],
            ['2yeAoJQGPiG&oxO', '$P$B7bOfjidDfxtAo7EKUxJbI/7Nb4HRC0'],
            ['MwZgNjHSQJHlB', '$P$BHJbG6eJKXfWW8ey2hVIfmY4TdSO8/.'],
            ['x(LeTr2Ye)', '$P$BTeOy9eaP4TvAtRetOhB6QhxLiILI..'],
            ['pTwIVfH)KSZ&M(c', '$P$BxUVysJqMIh5F/AYd.uGimja23Su681'],
            ['EtdF2ETwCsGu8d5', '$P$BptGvhDKrFsAECrlTRqtuxQFUqWxN..'],
            ['SipztGIx3o', '$P$BvSTZHq9km/iUOCZQoIyaNFp8lWW.E0'],
            ['LdgU6SFqQeF7T', '$P$BE2pzDkGCjeO5yv6fhgyIYKYHfbbYU/'],
            ['Fcn9LTnUZsDTzF6', '$P$BNa0MJtQm/piB2X9JNe53iyntbxAqs1'],
            ['PaK%4xMY1Lc(20R', '$P$BbfbWt5v0/E8Dcl0wV8GTekmkaHWKB1'],
            ['tMXG5Qle(', '$P$BlqWlVdvjJlbe3Z.SArSV5Pnl9ogOP.'],
            ['BP8kZmlS6^', '$P$BqMhSZmlDgUr7dTNJSXBF.tPF0gBHJ.'],
            ['DH)r8AXal', '$P$ByfIw9ERD0aocoASiyNN6u97H4w.it/'],
            ['AEUp)Kqd$rt%vrw', '$P$B7hvDya2nGXWy1ndaDjAaGjH75o5Z61'],
            ['L2@xiACc', '$P$B0cp1nmZh4b..rI7wI4ovHbVJN7Nrn1'],
            ['V78ieYTFsoz62BI', '$P$BZTcL62etXpNqZN0diFIoAmWVB7YwK/'],
            ['atWj6mv4pZI2Bnyl', '$P$BoruXe0.NWi4ja3GeL9stMZOFOpWcp1'],
            ['cl*ZA6$zLDd', '$P$B5jITKHD3QF/Xa9nKN0Q7K1f5VwwD.0'],
            ['9!aNAsx^*&O8tR', '$P$BzVvl1BYFnUGrgkRKNO5zQoSYLtVkh/'],
            ['Of!1X8wxe!Sv', '$P$BXPjZP.Q4c1UtCWnIJbPe.3CgX4FDN/'],
            ['$(b)wJeioTf#*DB^', '$P$BluxN.Y/s9NPlKvgLVS.B8Gf1dn2l2/'],
            ['Ibzu53kt*GNy', '$P$BvU6RRxMfTx7.3Gq8d5g/T3zvjnEaR/'],
            ['93@x2%NL', '$P$Bt2VPXA54D4DlcyCAWrJEPM3L/odPJ/'],
            ['DJ27JTK1', '$P$B3XztgL.HoyoSBTXrXI9QemudfWV7b0'],
            ['W9Sdfn2ZFk5QJizp', '$P$BR/ieSSO27DalRrCFQErvFdRwn6Qt0.'],
            ['x(wJ7#4QStERyx%l', '$P$BFFy68ClLmnVuay6/Y8rqDHx/aqus.0'],
            ['Gd#s15WfoC$eJpS2', '$P$Brrjhj7Wzm9BarGp54hwnPMFAJd5RG0'],
            ['VgZOrRMXi5MdvF', '$P$BT/9nNtt2IvKE0Di8xaEMDpi8Aka2f0'],
            ['yxmMDRVgZ5vui', '$P$BgQ2KBmKqgXycJ5Ca8Ou1babY3GLzU1'],
            ['jTx)OM79K', '$P$BnYSBlIWrWlEV46/kuGq6bX6zCN.uk0'],
            ['9ps!jiwVUF)baFg', '$P$BuEjjSJCPl1A8eoabnQek7VCHVhEhM1'],
            ['#ScYFG3a!', '$P$BVdrY8zAXJvG28zPae9FCbii076QAh1'],
            ['Q4m4uc3r1Ik4f6', '$P$BxbqE3mxUeI4FL5HfkFU3bgQ7Rzaki/'],
            ['Cgzgkhye', '$P$B1VR1qe3lCuzZP3hFn4rLAASXfdSX3/'],
            ['2nErGMPUe4as', '$P$Bbg/Txe5i.9SIVMwzfqkJNSNXPd6y.1'],
            [')2zj^ahcsyaK9', '$P$B5f1cJRtN.RJbcozKXko3bOGzkcXVy1'],
            ['DII^!K#s(&W', '$P$BeACSIFBb6mGIzOMyFq9dFPOjkfJ/O/'],
            ['b$Ww$yv1', '$P$B7P3/maG958/4gLg6hR7LM0AWnwri//'],
            ['9vgHSHhdP6yA', '$P$B5Q7fGkonllGq8kDeJoNWnKkT.FeLi.'],
            ['Gd58t)q#Ec@', '$P$Bu2EVsJW.iN47jiVuDhTr/IYHWJi6Y.'],
            ['cdlcVbtQakFPGTB', '$P$BqWugSe7iagUXK383/mur2u82ON8Mp0'],
            ['vgsZNrcBK', '$P$BESv9xSeGdMvU1qKWBbmoBh3n45h0V.'],
            ['x1!(l^!cTZS^N', '$P$BZtcQQVwZ0OZsaUJc6DIuL896Hx.ry/'],
            ['r2kouRqfbSOB2*', '$P$Bbk3xfRNJ0uzBsZoJaU9PJ9Uc4nb.E/'],
            ['LqGQMhHvH7Oa', '$P$BRla8N2ywvgLIdD5M.aaU1.TQIE7t8.'],
            ['cgMk6&bAkGln6I1', '$P$BlEjnKVIoQ6bZ2lUAWiQLyXM6cF2Bh1'],
            ['ITmJWXdHcskSqglw', '$P$BnB1Um8HrVnItQuPzOuKin2.LuXTrU1'],
            ['se19NAXI', '$P$B.StYLEU16NnShdy..YhawhBzFGeLn.'],
            ['K3f#YUiks&9@WM', '$P$BEe8dtMUfkfPxrPtneBx5ydDpVCS8s/'],
            ['(6GUAhVl8*W', '$P$BD2gD/B6J.sEXnGdhJWcQozHMuRpnV1'],
            ['q%#my5C)0!bJ%qdU', '$P$B3bgtyEDFnmQ6XtdIIytzOtXrQObVZ0'],
            ['SMNDRoA%vQuA%', '$P$BPEA0DnkPVghL6O0LE4GA86jgtFNnf1'],
            ['g9u^wOh(&$$', '$P$BPPbKl7Fhwe7Sud56jGMBzhuAIk5DR.'],
            ['anmTe@yv6mTPij5y', '$P$BxY0b7HYmB017Tmae8RK4ifF.IBv3G.'],
            ['ca)$IyptyzvkTXtv', '$P$Bw42rfXU59bD6iB56z8N6MDTRxvwR51'],
            ['jqAGrx2pMQFx', '$P$BuqADJ6iDleQe/IoyqCSjJOKlycBMa.'],
            ['FfTBQR6l2c(S', '$P$BAQFchYr/QBso4eglFIPR6uE3SBs5H.'],
            ['YGrCtK6t2u!TBLl$', '$P$BcDyd.4zXNEBaXHVA5pKoGl8qwHh/a0'],
            ['Rx8k0d4TUhSxNwGi', '$P$BvnDyNN3TEWMGQxYjbUsXV2xqs0sPD1'],
            ['SGnt!eOfA)m%I4', '$P$BPhooy45lbrrRUb.YGhoJmgIj5lnR2.'],
            ['Eq1lj791LpLb6Ny', '$P$BbhfGwVoJkWYwPNl6AKtHoPyIpFCGk1'],
            ['OjzKjBhz2QC3Y', '$P$BRM.yF2gP0nngtV937Wiqbfupl8b5c/'],
            ['qPU282w1D2S1f', '$P$BccqpiPkDjM3ASW6Cj7QCVZi9wipNR0'],
            ['VfACpJcD$TuJ', '$P$Bw4A6c4S//iR9qSHXdzAy.xCjuqZPQ1'],
            ['Uuofd3wqkwAwe', '$P$B2lYcq81r/Lwe5AdeDGPY/.poiK8aT0'],
            ['d5JwLEO1u', '$P$B1LlVg5a0QcfzCFl./I166x9eoSmrY0'],
            ['O74PZcq8JnYLTb', '$P$BXn3cXZOUqHeU2zaljwo/7anYmOFtn1'],
            ['1JtUntix5j', '$P$BUC4pDoFpZpL3FlDH9rdINsuSqYffG1'],
            ['dQD8dbNQIGHyD', '$P$B6FXyZIKXheE00YM2IxSrJIv.vHVwg/'],
            ['z6SHTxLIbbbgIuM', '$P$BUuQ9ZFr.PHGK4NJrQ/i7Xgb7jgGgh/'],
        ];
    }
}
