<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ingredient;
use App\Entity\Condiment;
use App\Entity\Recipe;
use App\Enum\RecipeLevel;
use App\Enum\RecipeSeason;
use App\Enum\RecipeMainCategory;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeCondiment;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $poulet = new Ingredient();
        $poulet->setName('Cuisse de poulet');
        $manager->persist($poulet);

        $feculeDePommeDeTerre = new Ingredient();
        $feculeDePommeDeTerre->setName('Fécule de pomme de terre');
        $manager->persist($feculeDePommeDeTerre);

        $ail = new Ingredient();
        $ail->setName('Ail');
        $manager->persist($ail);

        $gingembre = new Ingredient();
        $gingembre->setName('Gingembre');
        $manager->persist($gingembre);

        $oeuf = new Ingredient();
        $oeuf->setName('Oeufs');
        $manager->persist($oeuf);

        $Oignon = new Ingredient();
        $Oignon->setName('Oignon');
        $manager->persist($Oignon);

        $riz = new Ingredient();
        $riz->setName('Riz');
        $manager->persist($riz);

        $haricotsVerts = new Ingredient();
        $haricotsVerts->setName('Haricots verts');
        $manager->persist($haricotsVerts);

        $grainesDeSesameMoulues = new Ingredient();
        $grainesDeSesameMoulues->setName('Graines de sésame moulues');
        $manager->persist($grainesDeSesameMoulues);

        $tofuSoyeux = new Ingredient();
        $tofuSoyeux->setName('Tofu soyeux');
        $manager->persist($tofuSoyeux);

        $ciboule = new Ingredient();
        $ciboule->setName('Ciboule');
        $manager->persist($ciboule);

        $porcHache = new Ingredient();
        $porcHache->setName('Porc haché');
        $manager->persist($porcHache);

        $poireau = new Ingredient();
        $poireau->setName('Poireau');
        $manager->persist($poireau);

        $saumon = new Ingredient();
        $saumon->setName('Saumon');
        $manager->persist($saumon);

        $grainesDeSesame = new Ingredient();
        $grainesDeSesame->setName('Graines de sésame');
        $manager->persist($grainesDeSesame);

        $saucesoja = new Condiment();
        $saucesoja->setName('Sauce soja');
        $saucesoja->setExplanation('Condiment liquide fermenté à base de soja, de blé et de sel, indispensable dans la cuisine japonaise.');
        $saucesoja->setComposition('Soja, blé, sel, eau');
        $saucesoja->setUse('Karaage, Oyakodon, Tamagoyaki, Salade de haricots verts au sésame, Hiyayakko, Mapo tofu, Donburi au saumon mariné');
        $saucesoja->setUrl('https://www.amazon.fr/Kikkoman-Sauce-Soja-Flacon-150/dp/B001JTBDM0');
        $saucesoja->setImageUrl('https://placehold.co/400x300/png?text=Sauce+soja');
        $manager->persist($saucesoja);

        $sake = new Condiment();
        $sake->setName('Saké de cuisine');
        $sake->setExplanation('Alcool de riz fermenté utilisé en cuisine pour attendrir les viandes et atténuer les odeurs fortes.');
        $sake->setComposition('Riz, koji, eau');
        $sake->setUse('Karaage, Oyakodon, Mapo tofu');
        $sake->setUrl('https://www.amazon.fr/Umami-Sak%C3%A9-pour-cuisiner-500ml/dp/B0B59WD9TM');
        $sake->setImageUrl('https://placehold.co/400x300/png?text=Sake');
        $manager->persist($sake);

        $mirin = new Condiment();
        $mirin->setName('Mirin');
        $mirin->setExplanation('Vin de riz doux et sucré, à faible teneur en alcool, apportant brillance et douceur aux plats japonais.');
        $mirin->setComposition('Riz gluant, koji, alcool de riz');
        $mirin->setUse('Oyakodon, Donburi au saumon mariné');
        $mirin->setUrl('https://www.amazon.fr/Kikkoman-Mirin-cuisine-japonaise-1x750ml/dp/B004XWON0E');
        $mirin->setImageUrl('https://placehold.co/400x300/png?text=Mirin');
        $manager->persist($mirin);

        $sucre = new Condiment();
        $sucre->setName('Sucre');
        $sucre->setExplanation('Sucre blanc classique, utilisé pour équilibrer les saveurs salées et umami de la cuisine japonaise.');
        $sucre->setComposition('Canne à sucre ou betterave sucrière');
        $sucre->setUse('Tamagoyaki, Oyakodon, Salade de haricots verts au sésame');
        $sucre->setUrl(null);
        $sucre->setImageUrl('https://placehold.co/400x300/png?text=Sucre');
        $manager->persist($sucre);

        $sel = new Condiment();
        $sel->setName('Sel');
        $sel->setExplanation('Sel de table classique, utilisé pour assaisonner et rehausser les saveurs naturelles des ingrédients.');
        $sel->setComposition('Chlorure de sodium');
        $sel->setUse('Tamagoyaki');
        $sel->setUrl(null);
        $sel->setImageUrl('https://placehold.co/400x300/png?text=Sel');
        $manager->persist($sel);

        $miso = new Condiment();
        $miso->setName('Miso');
        $miso->setExplanation('Pâte fermentée à base de soja, riche en umami. Il existe plusieurs types : blanc, rouge, mélangé.');
        $miso->setComposition('Soja, riz, sel, koji');
        $miso->setUse('Mapo tofu');
        $miso->setUrl('https://www.amazon.fr/Hikari-Miso-Rouge-400G/dp/B007GGLV5Y');
        $miso->setImageUrl('https://placehold.co/400x300/png?text=Miso');
        $manager->persist($miso);

        $wasabi = new Condiment();
        $wasabi->setName('Wasabi');
        $wasabi->setExplanation('Racine japonaise au goût piquant, souvent remplacée hors du Japon par un mélange de raifort et de moutarde teinté en vert.');
        $wasabi->setComposition('Raifort, huile de moutarde, colorants (substitut courant du wasabi authentique)');
        $wasabi->setUse('Donburi au saumon mariné');
        $wasabi->setNote('Le wasabi authentique (Wasabia japonica) est rare et coûteux ; la plupart des produits vendus en Europe sont des substituts à base de raifort.');
        $wasabi->setUrl('https://www.amazon.fr/P%C3%A2te-wasabi-tube-43g/dp/B09Z6D99BZ');
        $wasabi->setImageUrl('https://placehold.co/400x300/png?text=Wasabi');
        $manager->persist($wasabi);


        $karaage = new Recipe();
        $karaage->setName('Karaage (Poulet frit japonais)');
        $karaage->setStep('1. Couper le poulet en morceaux et mariner avec la sauce soja et le saké pendant 10 minutes. 2. Ajouter l\'ail et le gingembre râpés. 3. Enrober les morceaux de fécule de pomme de terre. 4. Faire frire dans l\'huile chaude jusqu\'à ce que ce soit doré et croustillant.');
        $karaage->setSeason(RecipeSeason::ALL_YEAR);
        $karaage->setTime('20 min');
        $karaage->setLevel(RecipeLevel::EASY);
        $karaage->setMainCategory(RecipeMainCategory::MEAT);
        $manager->persist($karaage);

        $tamagoyaki = new Recipe();
        $tamagoyaki->setName('Tamagoyaki (Omelette japonaise)');
        $tamagoyaki->setStep('1. Battre les Oeufs avec la sauce soja, le sucre et le sel. 2. Verser une fine couche dans une poêle chaude huilée. 3. Rouler l\'omelette au fur et à mesure de la cuisson en ajoutant de nouvelles couches. 4. Couper en tranches.');
        $tamagoyaki->setSeason(RecipeSeason::ALL_YEAR);
        $tamagoyaki->setTime('10 min');
        $tamagoyaki->setLevel(RecipeLevel::EASY);
        $tamagoyaki->setMainCategory(RecipeMainCategory::EGG);
        $manager->persist($tamagoyaki);

        $oyakodon = new Recipe();
        $oyakodon->setName('Oyakodon (Poulet et Oeuf sur riz)');
        $oyakodon->setStep('1. Faire chauffer la sauce soja, le mirin, le saké et le sucre dans une poêle. 2. Ajouter le poulet et l\'oignon émincé, laisser mijoter. 3. Verser les Oeufs battus par-dessus et couvrir jusqu\'à cuisson désirée. 4. Servir sur un bol de riz chaud.');
        $oyakodon->setSeason(RecipeSeason::ALL_YEAR);
        $oyakodon->setTime('20 min');
        $oyakodon->setLevel(RecipeLevel::MEDIUM);
        $oyakodon->setMainCategory(RecipeMainCategory::MEAT);
        $manager->persist($oyakodon);

        $haricotsGomaae = new Recipe();
        $haricotsGomaae->setName('Salade de haricots verts au sésame');
        $haricotsGomaae->setStep('1. Faire cuire les haricots verts à l\'eau bouillante quelques minutes. 2. Égoutter et laisser refroidir. 3. Mélanger avec la sauce soja, le sucre et les graines de sésame moulues.');
        $haricotsGomaae->setSeason(RecipeSeason::ALL_YEAR);
        $haricotsGomaae->setTime('10 min');
        $haricotsGomaae->setLevel(RecipeLevel::EASY);
        $haricotsGomaae->setMainCategory(RecipeMainCategory::VEGETABLE);
        $manager->persist($haricotsGomaae);

        $hiyayakko = new Recipe();
        $hiyayakko->setName('Hiyayakko (Tofu froid)');
        $hiyayakko->setStep('1. Sortir le tofu soyeux de son emballage et l\'égoutter délicatement. 2. Le disposer dans un bol. 3. Garnir de ciboule émincée et de gingembre râpé. 4. Arroser de sauce soja.');
        $hiyayakko->setSeason(RecipeSeason::SUMMER);
        $hiyayakko->setTime('5 min');
        $hiyayakko->setLevel(RecipeLevel::EASY);
        $hiyayakko->setMainCategory(RecipeMainCategory::TOFU);
        $manager->persist($hiyayakko);

        $mapoTofu = new Recipe();
        $mapoTofu->setName('Mapo tofu');
        $mapoTofu->setStep('1. Faire revenir le porc haché avec l\'ail et le gingembre émincés. 2. Ajouter le miso, la sauce soja et le saké, mélanger. 3. Ajouter le tofu soyeux coupé en cubes délicatement. 4. Laisser mijoter puis garnir de poireau émincé.');
        $mapoTofu->setSeason(RecipeSeason::ALL_YEAR);
        $mapoTofu->setTime('25 min');
        $mapoTofu->setLevel(RecipeLevel::MEDIUM);
        $mapoTofu->setMainCategory(RecipeMainCategory::MEAT);
        $manager->persist($mapoTofu);

        $donburiSaumon = new Recipe();
        $donburiSaumon->setName('Donburi au saumon mariné');
        $donburiSaumon->setStep('1. Couper le saumon en cubes. 2. Préparer une marinade avec la sauce soja et le mirin. 3. Laisser mariner le saumon 10 minutes. 4. Servir sur un bol de riz avec la ciboule, le sésame et le wasabi.');
        $donburiSaumon->setSeason(RecipeSeason::ALL_YEAR);
        $donburiSaumon->setTime('15 min');
        $donburiSaumon->setLevel(RecipeLevel::EASY);
        $donburiSaumon->setMainCategory(RecipeMainCategory::FISH);
        $manager->persist($donburiSaumon);

        $ri1 = new RecipeIngredient();
        $ri1->setRecipe($karaage);
        $ri1->setIngredient($poulet);
        $ri1->setQuantity(150);
        $ri1->setUnit('g');
        $manager->persist($ri1);

        $ri2 = new RecipeIngredient();
        $ri2->setRecipe($karaage);
        $ri2->setIngredient($feculeDePommeDeTerre);
        $ri2->setQuantity(3);
        $ri2->setUnit('c. à s.');
        $manager->persist($ri2);

        $ri3 = new RecipeIngredient();
        $ri3->setRecipe($karaage);
        $ri3->setIngredient($ail);
        $ri3->setQuantity(0.5);
        $ri3->setUnit('c. à c.');
        $manager->persist($ri3);

        $ri4 = new RecipeIngredient();
        $ri4->setRecipe($karaage);
        $ri4->setIngredient($gingembre);
        $ri4->setQuantity(0.5);
        $ri4->setUnit('c. à c.');
        $manager->persist($ri4);

        $rc1 = new RecipeCondiment();
        $rc1->setRecipe($karaage);
        $rc1->setCondiment($saucesoja);
        $rc1->setQuantity(1);
        $rc1->setUnit('c. à s.');
        $manager->persist($rc1);

        $rc2 = new RecipeCondiment();
        $rc2->setRecipe($karaage);
        $rc2->setCondiment($sake);
        $rc2->setQuantity(1);
        $rc2->setUnit('c. à s.');
        $manager->persist($rc2);

        // 卵焼き
        $ri5 = new RecipeIngredient();
        $ri5->setRecipe($tamagoyaki);
        $ri5->setIngredient($oeuf);
        $ri5->setQuantity(2);
        $ri5->setUnit('unité(s)');
        $manager->persist($ri5);

        $rc3 = new RecipeCondiment();
        $rc3->setRecipe($tamagoyaki);
        $rc3->setCondiment($saucesoja);
        $rc3->setQuantity(1);
        $rc3->setUnit('c. à c.');
        $manager->persist($rc3);

        $rc4 = new RecipeCondiment();
        $rc4->setRecipe($tamagoyaki);
        $rc4->setCondiment($sucre);
        $rc4->setQuantity(2);
        $rc4->setUnit('c. à c.');
        $manager->persist($rc4);

        $rc5 = new RecipeCondiment();
        $rc5->setRecipe($tamagoyaki);
        $rc5->setCondiment($sel);
        $rc5->setQuantity(1);
        $rc5->setUnit('pincée');
        $manager->persist($rc5);

        // 親子丼
        $ri6 = new RecipeIngredient();
        $ri6->setRecipe($oyakodon);
        $ri6->setIngredient($poulet);
        $ri6->setQuantity(100);
        $ri6->setUnit('g');
        $manager->persist($ri6);

        $ri7 = new RecipeIngredient();
        $ri7->setRecipe($oyakodon);
        $ri7->setIngredient($oeuf);
        $ri7->setQuantity(2);
        $ri7->setUnit('unité(s)');
        $manager->persist($ri7);

        $ri8 = new RecipeIngredient();
        $ri8->setRecipe($oyakodon);
        $ri8->setIngredient($Oignon);
        $ri8->setQuantity(0.25);
        $ri8->setUnit('unité(s)');
        $manager->persist($ri8);

        $ri9 = new RecipeIngredient();
        $ri9->setRecipe($oyakodon);
        $ri9->setIngredient($riz);
        $ri9->setQuantity(1);
        $ri9->setUnit('bol');
        $manager->persist($ri9);

        $rc6 = new RecipeCondiment();
        $rc6->setRecipe($oyakodon);
        $rc6->setCondiment($saucesoja);
        $rc6->setQuantity(1.5);
        $rc6->setUnit('c. à s.');
        $manager->persist($rc6);

        $rc7 = new RecipeCondiment();
        $rc7->setRecipe($oyakodon);
        $rc7->setCondiment($mirin);
        $rc7->setQuantity(1.5);
        $rc7->setUnit('c. à s.');
        $manager->persist($rc7);

        $rc8 = new RecipeCondiment();
        $rc8->setRecipe($oyakodon);
        $rc8->setCondiment($sake);
        $rc8->setQuantity(1);
        $rc8->setUnit('c. à s.');
        $manager->persist($rc8);

        $rc9 = new RecipeCondiment();
        $rc9->setRecipe($oyakodon);
        $rc9->setCondiment($sucre);
        $rc9->setQuantity(1);
        $rc9->setUnit('c. à c.');
        $manager->persist($rc9);

        // いんげんの胡麻和え
        $ri10 = new RecipeIngredient();
        $ri10->setRecipe($haricotsGomaae);
        $ri10->setIngredient($haricotsVerts);
        $ri10->setQuantity(100);
        $ri10->setUnit('g');
        $manager->persist($ri10);

        $ri11 = new RecipeIngredient();
        $ri11->setRecipe($haricotsGomaae);
        $ri11->setIngredient($grainesDeSesameMoulues);
        $ri11->setQuantity(2);
        $ri11->setUnit('c. à s.');
        $manager->persist($ri11);

        $rc10 = new RecipeCondiment();
        $rc10->setRecipe($haricotsGomaae);
        $rc10->setCondiment($saucesoja);
        $rc10->setQuantity(2);
        $rc10->setUnit('c. à c.');
        $manager->persist($rc10);

        $rc11 = new RecipeCondiment();
        $rc11->setRecipe($haricotsGomaae);
        $rc11->setCondiment($sucre);
        $rc11->setQuantity(1);
        $rc11->setUnit('c. à c.');
        $manager->persist($rc11);

        // 冷奴
        $ri12 = new RecipeIngredient();
        $ri12->setRecipe($hiyayakko);
        $ri12->setIngredient($tofuSoyeux);
        $ri12->setQuantity(150);
        $ri12->setUnit('g');
        $manager->persist($ri12);

        $ri13 = new RecipeIngredient();
        $ri13->setRecipe($hiyayakko);
        $ri13->setIngredient($ciboule);
        $ri13->setQuantity(1);
        $ri13->setUnit('c. à s.');
        $manager->persist($ri13);

        $ri14 = new RecipeIngredient();
        $ri14->setRecipe($hiyayakko);
        $ri14->setIngredient($gingembre);
        $ri14->setQuantity(0.5);
        $ri14->setUnit('c. à c.');
        $manager->persist($ri14);

        $rc12 = new RecipeCondiment();
        $rc12->setRecipe($hiyayakko);
        $rc12->setCondiment($saucesoja);
        $rc12->setQuantity(1);
        $rc12->setUnit('c. à s.');
        $manager->persist($rc12);

        // 麻婆豆腐
        $ri15 = new RecipeIngredient();
        $ri15->setRecipe($mapoTofu);
        $ri15->setIngredient($tofuSoyeux);
        $ri15->setQuantity(150);
        $ri15->setUnit('g');
        $manager->persist($ri15);

        $ri16 = new RecipeIngredient();
        $ri16->setRecipe($mapoTofu);
        $ri16->setIngredient($porcHache);
        $ri16->setQuantity(80);
        $ri16->setUnit('g');
        $manager->persist($ri16);

        $ri17 = new RecipeIngredient();
        $ri17->setRecipe($mapoTofu);
        $ri17->setIngredient($ail);
        $ri17->setQuantity(0.5);
        $ri17->setUnit('c. à c.');
        $manager->persist($ri17);

        $ri18 = new RecipeIngredient();
        $ri18->setRecipe($mapoTofu);
        $ri18->setIngredient($gingembre);
        $ri18->setQuantity(0.5);
        $ri18->setUnit('c. à c.');
        $manager->persist($ri18);

        $ri19 = new RecipeIngredient();
        $ri19->setRecipe($mapoTofu);
        $ri19->setIngredient($poireau);
        $ri19->setQuantity(2);
        $ri19->setUnit('c. à s.');
        $manager->persist($ri19);

        $rc13 = new RecipeCondiment();
        $rc13->setRecipe($mapoTofu);
        $rc13->setCondiment($miso);
        $rc13->setQuantity(1);
        $rc13->setUnit('c. à s.');
        $manager->persist($rc13);

        $rc14 = new RecipeCondiment();
        $rc14->setRecipe($mapoTofu);
        $rc14->setCondiment($saucesoja);
        $rc14->setQuantity(1);
        $rc14->setUnit('c. à c.');
        $manager->persist($rc14);

        $rc15 = new RecipeCondiment();
        $rc15->setRecipe($mapoTofu);
        $rc15->setCondiment($sake);
        $rc15->setQuantity(1);
        $rc15->setUnit('c. à s.');
        $manager->persist($rc15);

        // 漬け丼
        $ri20 = new RecipeIngredient();
        $ri20->setRecipe($donburiSaumon);
        $ri20->setIngredient($saumon);
        $ri20->setQuantity(120);
        $ri20->setUnit('g');
        $manager->persist($ri20);

        $ri21 = new RecipeIngredient();
        $ri21->setRecipe($donburiSaumon);
        $ri21->setIngredient($riz);
        $ri21->setQuantity(1);
        $ri21->setUnit('bol');
        $manager->persist($ri21);

        $ri22 = new RecipeIngredient();
        $ri22->setRecipe($donburiSaumon);
        $ri22->setIngredient($ciboule);
        $ri22->setQuantity(1);
        $ri22->setUnit('c. à s.');
        $manager->persist($ri22);

        $ri23 = new RecipeIngredient();
        $ri23->setRecipe($donburiSaumon);
        $ri23->setIngredient($grainesDeSesame);
        $ri23->setQuantity(1);
        $ri23->setUnit('c. à c.');
        $manager->persist($ri23);

        $rc16 = new RecipeCondiment();
        $rc16->setRecipe($donburiSaumon);
        $rc16->setCondiment($saucesoja);
        $rc16->setQuantity(1.5);
        $rc16->setUnit('c. à s.');
        $manager->persist($rc16);

        $rc17 = new RecipeCondiment();
        $rc17->setRecipe($donburiSaumon);
        $rc17->setCondiment($mirin);
        $rc17->setQuantity(1);
        $rc17->setUnit('c. à c.');
        $manager->persist($rc17);

        $rc18 = new RecipeCondiment();
        $rc18->setRecipe($donburiSaumon);
        $rc18->setCondiment($wasabi);
        $rc18->setQuantity(0.5);
        $rc18->setUnit('c. à c.');
        $manager->persist($rc18);

        $manager->flush();
    }
}
