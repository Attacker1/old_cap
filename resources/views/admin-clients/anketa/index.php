<?php define('PUBLIC_PATH', 'anketa/completed/'); ?>
<!DOCTYPE html>
<html>
<head>
    <?php include PUBLIC_PATH . "common/head.php"; ?>
</head>
<body>

<?php
    $link = route('admin-clients.orders.list');
    echo <<<HTML
<nav class="top-header-nav">
    <div class="top-header-nav-item">
        <a href="{$link}" class="anketa-exit-btn">
            <svg fill="#3f4254" height="17px" viewBox="0 0 511 512" width="17px" xmlns="http://www.w3.org/2000/svg">
                <path d="m361.5 392v40c0 44.113281-35.886719 80-80 80h-201c-44.113281 0-80-35.886719-80-80v-352c0-44.113281 35.886719-80 80-80h201c44.113281 0 80 35.886719 80 80v40c0 11.046875-8.953125 20-20 20s-20-8.953125-20-20v-40c0-22.054688-17.945312-40-40-40h-201c-22.054688 0-40 17.945312-40 40v352c0 22.054688 17.945312 40 40 40h201c22.054688 0 40-17.945312 40-40v-40c0-11.046875 8.953125-20 20-20s20 8.953125 20 20zm136.355469-170.355469-44.785157-44.785156c-7.8125-7.8125-20.476562-7.8125-28.285156 0-7.8125 7.808594-7.8125 20.472656 0 28.28125l31.855469 31.859375h-240.140625c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h240.140625l-31.855469 31.859375c-7.8125 7.808594-7.8125 20.472656 0 28.28125 3.90625 3.90625 9.023438 5.859375 14.140625 5.859375 5.121094 0 10.238281-1.953125 14.144531-5.859375l44.785157-44.785156c19.496093-19.496094 19.496093-51.214844 0-70.710938zm0 0"/>
            </svg>
            <span>вернуться в ЛК</span>
        </a>
    </div>
</nav>
HTML;

?>
<div id='app'>
    <div class="wrap">

        <div class='main' v-cloak v-if="anketa">

<?php

    //include PUBLIC_PATH . "common/stepper.php";//stepper


    //с чем Capsula может вам помочь для C q_69 [78] whatHelp
    if(isset($anketa['question'][78]))
        if($anketa['question'][78]['answer'])
            include PUBLIC_PATH . "common/whatHelp.php";

    //Как зовут q_0  [0] whatName
    if(isset($anketa['question'][0]))
        if($anketa['question'][0]['answer'] !== '')
            include PUBLIC_PATH . "common/whatName.php";


    //для какой цели подборку q_58 [62] whatPurpose
    if(isset($anketa['question'][62]))
        if(is_array($anketa['question'][62]['answer']))
            if(count($anketa['question'][62]['answer']) > 0)
                include PUBLIC_PATH . "common/whatPurpose.php";

    //TODO имя клиента
    //Стиль в выходные q_1 [1] styleOnWeekend
    if(isset($anketa['question'][1]))
        if(is_array($anketa['question'][1]['answer']))
            if(count($anketa['question'][1]['answer']) > 0)
                include PUBLIC_PATH . "common/styleOnWeekend.php";

    //стиль на работу q_2 [2] styleOnWork
    if(isset($anketa['question'][2]))
        if(is_array($anketa['question'][2]['answer']))
            if(count($anketa['question'][2]['answer']) > 0)
                include PUBLIC_PATH . "common/styleOnWork.php";

    //подобрать отличное или сохранить стиль q_3 [3] tryOtherOrSaveStyle
    if(isset($anketa['question'][3]))
        if($anketa['question'][3]['answer'] !== '')
            include PUBLIC_PATH . "common/tryOtherOrSaveStyle.php";

    //образы q_4 по q_13
    //образ q_4 [4] lifestyles choosingStyle4
    if(isset($anketa['question'][4]))
        if(is_array($anketa['question'][4]['answer']))
            if(count($anketa['question'][4]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle4.php";

    //образ q_5 [5] lifestyles choosingStyle5
    if(isset($anketa['question'][5]))
        if(is_array($anketa['question'][5]['answer']))
            if(count($anketa['question'][5]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle5.php";

    //образ q_6 [6] lifestyles choosingStyle6
    if(isset($anketa['question'][6]))
        if(is_array($anketa['question'][6]['answer']))
            if(count($anketa['question'][6]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle6.php";

    //образ q_7 [7] lifestyles choosingStyle7
    if(isset($anketa['question'][7]))
        if(is_array($anketa['question'][7]['answer']))
            if(count($anketa['question'][7]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle7.php";

    //образ q_8 [8] lifestyles choosingStyle8
    if(isset($anketa['question'][8]))
        if(is_array($anketa['question'][8]['answer']))
            if(count($anketa['question'][8]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle8.php";

    //образ q_9 [9] lifestyles choosingStyle9
    if(isset($anketa['question'][9]))
        if(is_array($anketa['question'][9]['answer']))
            if(count($anketa['question'][9]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle9.php";

    //образ q_10 [10] lifestyles choosingStyle10
    if(isset($anketa['question'][10]))
        if(is_array($anketa['question'][10]['answer']))
            if(count($anketa['question'][10]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle10.php";

    //образ q_11 [11] lifestyles choosingStyle11
    if(isset($anketa['question'][11]))
        if(is_array($anketa['question'][11]['answer']))
            if(count($anketa['question'][11]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle11.php";

    //образ q_12 [12] lifestyles choosingStyle12
    if(isset($anketa['question'][12]))
        if(is_array($anketa['question'][12]['answer']))
            if(count($anketa['question'][12]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle12.php";

    //образ q_13 [13] lifestyles choosingStyle13
    if(isset($anketa['question'][13]))
        if(is_array($anketa['question'][13]['answer']))
            if(count($anketa['question'][13]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle13.php";

    //more_lifestyle
    //образ q_15 [16] choosingStyle16
    if(isset($anketa['question'][16]))
        if(is_array($anketa['question'][16]['answer']))
            if(count($anketa['question'][16]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle16.php";

    //образ q_16 [17] choosingStyle17
    if(isset($anketa['question'][17]))
        if(is_array($anketa['question'][17]['answer']))
            if(count($anketa['question'][17]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle17.php";

    //образ q_17 [18] choosingStyle18
    if(isset($anketa['question'][18]))
        if(is_array($anketa['question'][18]['answer']))
            if(count($anketa['question'][18]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle18.php";

    //образ q_18 [19] choosingStyle19
    if(isset($anketa['question'][19]))
        if(is_array($anketa['question'][19]['answer']))
            if(count($anketa['question'][19]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle19.php";

    //образ q_19 [20] choosingStyle20
    if(isset($anketa['question'][20]))
        if(is_array($anketa['question'][20]['answer']))
            if(count($anketa['question'][20]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle20.php";

    //образ q_20 [21] choosingStyle21
    if(isset($anketa['question'][21]))
        if(is_array($anketa['question'][21]['answer']))
            if(count($anketa['question'][21]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle21.php";

    //образ q_21 [22] choosingStyle22
    if(isset($anketa['question'][22]))
        if(is_array($anketa['question'][22]['answer']))
            if(count($anketa['question'][22]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle22.php";

    //образ q_22 [23] choosingStyle23
    if(isset($anketa['question'][23]))
        if(is_array($anketa['question'][23]['answer']))
            if(count($anketa['question'][23]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle23.php";

    //образ q_23 [24] choosingStyle24
    if(isset($anketa['question'][24]))
        if(is_array($anketa['question'][24]['answer']))
            if(count($anketa['question'][24]['answer']) > 0)
                include PUBLIC_PATH . "common/lifestyles/choosingStyle24.php";

?>

<?php

    //цветовая гамма q_24 [25] choosingPalletes25
    if(isset($anketa['question'][25]))
        if(is_array($anketa['question'][25]['answer']))
            if(count($anketa['question'][25]['answer']) > 0)
                include PUBLIC_PATH . "common/choosingPalletes25.php";

    //TODO имя клиента
    //рост, вес q_25 [26] [70] heightAndWeight
    if(isset($anketa['question'][26]) && isset($anketa['question'][70]))
        if($anketa['question'][26]['answer'] !== '' || $anketa['question'][70]['answer'])
            include PUBLIC_PATH . "common/heightAndWeight.php";

    //дата рождения q_26 [27] birth
    if(isset($anketa['question'][27]))
        if($anketa['question'][27]['answer'] !== '' )
            include PUBLIC_PATH . "common/birth.php";

    //род деятельности q_27 [28] occupation
    if(isset($anketa['question'][28]))
        if($anketa['question'][28]['answer'] !== '' )
            include PUBLIC_PATH . "common/occupation.php";

    //цвет волос q_28 [29] hairColor
    if(isset($anketa['question'][29]))
        if($anketa['question'][29]['answer'] !== '')
            include PUBLIC_PATH . "common/hairColor.php";

    //размер верха q_29 [30] sizeTop
    if(isset($anketa['question'][30]))
        if(is_array($anketa['question'][30]['answer']))
            if(count($anketa['question'][30]['answer']) > 0)
                include PUBLIC_PATH . "common/sizeTop.php";

    //размер низа q_30 [31] sizeBottom
    if(isset($anketa['question'][31]))
        if(is_array($anketa['question'][31]['answer']))
            if(count($anketa['question'][31]['answer']) > 0)
                include PUBLIC_PATH . "common/sizeBottom.php";

    //90-60-90 q_31 [32,33,34] chestWaistHips
    if(isset($anketa['question'][32]) && isset($anketa['question'][33]) && isset($anketa['question'][34]))
        if($anketa['question'][32]['answer'] !=='' || $anketa['question'][33]['answer'] !== '' || $anketa['question'][34]['answer'] !== '')
            include PUBLIC_PATH . "common/chestWaistHips.php";

    //как комфортно носить верх q_32 [35] aboutTopStyle
    if(isset($anketa['question'][35]))
        if(is_array($anketa['question'][35]['answer']))
            if(count($anketa['question'][35]['answer']) > 0)
                include PUBLIC_PATH . "common/aboutTopStyle.php";

    //TODO имя клиента
    //как комфортно носить брюки/джинсы q_33 [36] aboutBottomStyle
    if(isset($anketa['question'][36]))
        if(is_array($anketa['question'][36]['answer']))
            if(count($anketa['question'][36]['answer']) > 0)
                include PUBLIC_PATH . "common/aboutBottomStyle.php";

    //какие модели джинсов вы предпочитаете q_34 [37] modelsJeans
    if(isset($anketa['question'][37]))
        if(is_array($anketa['question'][37]['answer']))
            if(count($anketa['question'][37]['answer']) > 0)
                include PUBLIC_PATH . "common/modelsJeans.php";

    //посадку джинсов вы предпочитаете q_35 [38] trousersFit
    if(isset($anketa['question'][38]))
        if(is_array($anketa['question'][38]['answer']))
            if(count($anketa['question'][38]['answer']) > 0)
                include PUBLIC_PATH . "common/trousersFit.php";

    //длина джинсов / брюк q_36 [39] trouserslength
    if(isset($anketa['question'][39]))
        if(is_array($anketa['question'][39]['answer']))
            if(count($anketa['question'][39]['answer']) > 0)
                include PUBLIC_PATH . "common/trouserslength.php";

    //Какие юбки / платья q_37 [40] dressesType
    if(isset($anketa['question'][40]))
        if(is_array($anketa['question'][40]['answer']))
            if(count($anketa['question'][40]['answer']) > 0)
                include PUBLIC_PATH . "common/dressesType.php"; //Какие юбки / платья q_37

    //TODO имя клиента
    //части тела, которые вы предпочитаете скрывать q_38 [41] bodyPartsToHide
    if(isset($anketa['question'][41]))
        if(is_array($anketa['question'][41]['answer']))
            if(count($anketa['question'][41]['answer']) > 0)
                include PUBLIC_PATH . "common/bodyPartsToHide.php";

    //джинсы с топом или платья q_39 [42] preferJeansWithTopOrDresses
    if(isset($anketa['question'][42]))
        if($anketa['question'][42]['answer'] !== '')
            include PUBLIC_PATH . "common/preferJeansWithTopOrDresses.php";

    //где покупаете одежду q_40 [43] whereUsuallyBuyClothes
    if(isset($anketa['question'][43]))
        if(is_array($anketa['question'][43]['answer']))
            if(count($anketa['question'][43]['answer']) > 0)
                include PUBLIC_PATH . "common/whereUsuallyBuyClothes.php";

    //Проколоты ли у вас уши q_41 [44] earsPierced
    if(isset($anketa['question'][44]))
        if ($anketa['question'][44]['answer'] !== '')
            include PUBLIC_PATH . "common/earsPierced.php";

    //Какие ювелирные изделия q_42 [45] jewelry
    if(isset($anketa['question'][45]))
        if ($anketa['question'][45]['answer'] !== '')
            include PUBLIC_PATH . "common/jewelry.php";

    //к бижутерии готовы q_43 [46] bijouterie
    if(isset($anketa['question'][46]))
        if ($anketa['question'][46]['answer'] !== '')
            include PUBLIC_PATH . "common/bijouterie.php";

    //что хотите получить в капсуле q_44 [47] capsulaFirstOfAll
    if(isset($anketa['question'][47]))
        if(is_array($anketa['question'][47]['answer']))
            if(count($anketa['question'][47]['answer']) > 0)
                include PUBLIC_PATH . "common/capsulaFirstOfAll.php";

    //что не хотели видеть в капсуле q_45 [48] capsulaNotFirstOfAll
    if(isset($anketa['question'][48]))
        if(is_array($anketa['question'][48]['answer']))
            if(count($anketa['question'][48]['answer']) > 0)
                include PUBLIC_PATH . "common/capsulaNotFirstOfAll.php";

    //TODO имя клиента
    //аксессуары вы не хотите получить q_46 [49] capsulaNotWantAccessories
    if(isset($anketa['question'][49]))
        if(is_array($anketa['question'][49]['answer']))
            if(count($anketa['question'][49]['answer']) > 0)
                include PUBLIC_PATH . "common/capsulaNotWantAccessories.php";

    //цвета вы бы НЕ хотели q_47 [50] noColor
    if(isset($anketa['question'][50]))
        if(is_array($anketa['question'][50]['answer']))
            if(count($anketa['question'][50]['answer']) > 0)
                include PUBLIC_PATH . "common/noColor.php";

    //Принты вы бы НЕ хотели q_48 [51] printsDislike
    if(isset($anketa['question'][51]))
        if(is_array($anketa['question'][51]['answer']))
            if(count($anketa['question'][51]['answer']) > 0)
                include PUBLIC_PATH . "common/printsDislike.php";

    //ткани вы бы НЕ хотели q_49 [52] fabricsShouldAvoid
    if(isset($anketa['question'][52]))
        if(is_array($anketa['question'][52]['answer']))
            if(count($anketa['question'][52]['answer']) > 0)
                include PUBLIC_PATH . "common/fabricsShouldAvoid.php";

    //напишите доп нюансы q_50 [53] additionalNuances
    if(isset($anketa['question'][53]))
        if($anketa['question'][53]['answer'] !=='')
            include PUBLIC_PATH . "common/additionalNuances.php";
?>

<?php
    //prices
    //цена блуза/рубашка q_51 [54] howMuchToSpendOnBlouseShirt
    if(isset($anketa['question'][54]))
        if($anketa['question'][54]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnBlouseShirt.php";

    //цена свитер/джемпер q_52 [55]  howMuchToSpendOnSweaterJumperPullover
    if(isset($anketa['question'][55]))
        if($anketa['question'][55]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnSweaterJumperPullover.php";

    //цена платья/сарафаны q_53 [56] howMuchToSpendOnDressesSundresses
    if(isset($anketa['question'][56]))
        if($anketa['question'][56]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnDressesSundresses.php";

    //цена жакет/пиджак q_54 [57] howMuchToSpendOnJacket
    if(isset($anketa['question'][57]))
        if($anketa['question'][57]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnJacket.php";

    //цена джинсы/брюки q_55 [58] howMuchToSpendOnJeansTrousersSkirts
    if(isset($anketa['question'][58]))
        if($anketa['question'][58]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnJeansTrousersSkirts.php";

    //цена сумки q_56 [59] howMuchToSpendOnBags
    if(isset($anketa['question'][59]))
        if($anketa['question'][59]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnBags.php";

    //цена другие аксессуары: ремни, шарфы, платки [60] howMuchToSpendOnBeltsScarvesShawls
    if(isset($anketa['question'][60]))
        if($anketa['question'][60]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnBeltsScarvesShawls.php";

    //цена аксессуары ремни q_57 [61] howMuchToSpendOnEarringsNecklacesBracelets
    if(isset($anketa['question'][61]))
        if($anketa['question'][61]['answer'] !== '')
            include PUBLIC_PATH . "common/prices/howMuchToSpendOnEarringsNecklacesBracelets.php";


    //TODO имя клиента
    //на какой сезон вы хотите подборку  q_73 [83] whatSeason
    if(isset($anketa['question'][83]))
        if($anketa['question'][83]['answer'] !== '')
            include PUBLIC_PATH . "common/whatSeason.php";

    //инстагр q_65 [68] socials
    if(isset($anketa['question'][68]))
        if($anketa['question'][68]['answer'] !== '')
            include PUBLIC_PATH . "common/personal_data/socials.php";


    //фото q_66 [72]  photosAttach
    if(isset($anketa['question'][72]))
        if($anketa['question'][72]['answer'] !== '')
            include PUBLIC_PATH . "common/personal_data/photosAttach.php";
?>

<?php

    //personal_data
    //TODO имя клиента
    //телефон емайл q_14 [14,15] emailPhone
    if(isset($anketa['question'][15]) && isset($anketa['question'][14]))
        if($anketa['question'][15]['answer']!=='' || $anketa['question'][14]['answer']!=='')
        include PUBLIC_PATH . "common/personal_data/emailPhone.php";

    //Фамилия для доставки q_64 [67] bioSurname
    if(isset($anketa['question'][67]))
        if($anketa['question'][67]['answer'] !== '')
            include PUBLIC_PATH . "common/personal_data/bioSurname.php";

    //фио для доставки q_64 [0] [79] [84] fio
    if(isset($anketa['question'][0]) && isset($anketa['question'][79]) && isset($anketa['question'][84]))
        if($anketa['question'][0]['answer'] !== '' || $anketa['question'][79]['answer'] !== '' || $anketa['question'][84]['answer'] !== '' )
            include PUBLIC_PATH . "common/personal_data/fio.php";

    //выбор города  q_59 [71] delivery
    if(isset($anketa['question'][71]))
        if(isset($anketa['question'][71]))
            if($anketa['question'][71]['answer'] !== '')
        include PUBLIC_PATH . "common/delivery/delivery.php";

    //курьер/самовывоз q_70 [80] deliveryType
    if(isset($anketa['question'][80]))
        if($anketa['question'][80]['answer'] !== '')
            include PUBLIC_PATH . "common/delivery/deliveryType.php";

    // адрес: город, улица q_60 [74] [76] [77] address
    if(isset($anketa['question'][74]) && isset($anketa['question'][76]) && isset($anketa['question'][77]))
        if($anketa['question'][74]['answer'] !=='' || $anketa['question'][76]['answer'] !=='' || $anketa['question'][77]['answer'] !=='')
            include PUBLIC_PATH . "common/delivery/address.php";

        //TODO не пишет в анкете
    //выбор города боксбери другой город q_72 [82] boxberryCity
    if(isset($anketa['question'][82]))
        if($anketa['question'][82]['answer'] !== '')
            include PUBLIC_PATH . "common/delivery/boxberryCity.php";

    //TODO не пишет в анкете
    //выбор боксбери q_71 [81]] boxberryPoint
    if(isset($anketa['question'][81]))
        if($anketa['question'][81]['answer'] !== '')
            include PUBLIC_PATH . "common/delivery/boxberryPoint.php";

    //адрес: город, улица q_60 [63] addresstOld старый
    if(isset($anketa['question'][63]))
        if($anketa['question'][63]['answer'] !== '')
            include PUBLIC_PATH . "common/delivery/addressOld.php";

    //когда получить дата q_61 [64] deliveryDate
    if(isset($anketa['question'][64]))
        if($anketa['question'][64]['answer'] !== '')
            include PUBLIC_PATH . "common/delivery/deliveryDate.php";

    //когда получить время q_62 [65] deliveryTime
    if(isset($anketa['question'][65]))
        if($anketa['question'][65]!=='')
            include PUBLIC_PATH . "common/delivery/deliveryTime.php";

    //когда забрать время q_63 [66] deliveryBackTime
    if(isset($anketa['question'][66]))
        if($anketa['question'][66]!=='')
                include PUBLIC_PATH . "common/delivery/deliveryBackTime.php";

    //откуда узнали q_67 [69] knewAboutUs
    if(isset($anketa['question'][69]))
        if($anketa['question'][69]['answer'] !== '')
            include PUBLIC_PATH . "common/knewAboutUs.php";

    //TODO q_68 [] стоимость, купон anketa.amount

?>

		</div>
	</div>
</div>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script>
	<?php

		echo "var anketa = " . json_encode($anketa, JSON_UNESCAPED_UNICODE) . "; ";

        if($env == "production") echo 'var env = "production"; ';
        else echo 'var env = "local"; ';
    ?>

        $('.anketa-exit-btn').on('click', ()=>{
            if(env == 'production') {
                ym(82667803,'reachGoal','goal_13')
            } else console.log('13');
        });

</script>
<script src= "/<?php echo PUBLIC_PATH; ?>js/scripts.js?<?= time() ?>"></script>

<?php
	if (intval($uuid_view)) {
		echo "<style>.next-question {visibility: hidden;}</style>";
	}
?>

</body>
</html>
