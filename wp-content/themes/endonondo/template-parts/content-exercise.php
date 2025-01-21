<?php
global $wpdb;
$postid = get_the_ID();
$exerciseId = get_post_meta($post->ID, 'exercise_name', true);
$exData = $wpdb->get_results(
    "SELECT * FROM {$wpdb->prefix}exercise WHERE id = " . $exerciseId
);

$iframe = '';

if (!empty($exData)) {
    $iframe = get_video($exData[0], true);
}

if ($exData):
    ?>
    <section class="exc-hero-section">
        <div class="container">
            <div class="exc-container">
                <?php if ($iframe): ?>
                    <div class="exc-video">
                        <?= $iframe ?>
                    </div>
                <?php endif; ?>
                <div class="exc-title">
                    <h1><?= $exData[0]->name ?></h1>
                </div>
                <div class="exc-description">
                    <?= $exData[0]->description ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    $contents = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}exercise_content WHERE exercise_id = " . $exerciseId,
        ARRAY_A
    );
    $contentPrimary = "";
    $contentSecondary = "";
    $contentEquipment = "";
    ?>
    <section class="exc-content">
        <div class="container">
            <div class="exc-container">
                <?php foreach ($contents as $content): ?>
                    <?php if ($content['content_type'] != 4 && $content['content_type'] != 5 && $content['content_type'] != 6 && $content['content_type'] != 2): ?>
                        <?php if (!empty($content['content'])): ?>
                            <div class="content-item bd-bot exercise-list-start">
                                <h2 class="title-content"><?= $content['content_title']; ?></h2>
                                <?= $content['content'] ?>
                            </div>
                        <?php endif; ?>
                    <?php else:
                        if ($content['content_type'] == 4) {
                            $contentPrimary = $content['content'];
                        } elseif ($content['content_type'] == 5) {
                            $contentSecondary = $content['content'];
                        } elseif ($content['content_type'] == 6) {
                            $contentEquipment = $content['content'];
                        }
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php
    $primaryIds = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}exercise_primary_option WHERE exercise_id = " . $exerciseId,
        ARRAY_A
    );

    $arrPrimaryId = array();
    foreach ($primaryIds as $primaryId) {
        $arrPrimaryId[] = $primaryId['muscle_id'];
    }

    $primaryDatas = [];
    $ids = '';
    if ($arrPrimaryId) {
        $ids = implode(',', $arrPrimaryId);

        $primaryDatas = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}exercise_muscle_anatomy WHERE id IN ({$ids}) AND active = 1",
            ARRAY_A
        );

        $muscle_ids = $arrPrimaryId;
        $exclude_exercise_id = $exerciseId;
        $placeholders = implode(',', array_fill(0, count($muscle_ids), '%d'));

        $query = "
                SELECT exercise_id
                FROM {$wpdb->prefix}exercise_primary_option
                WHERE muscle_id IN ($placeholders)
                AND exercise_id != %d
                GROUP BY exercise_id
            ";

        $prepared_query = $wpdb->prepare(
            $query,
            array_merge($muscle_ids, [$exclude_exercise_id])
        );

        $samePrimary = $wpdb->get_results($prepared_query, ARRAY_A);

        foreach ($samePrimary as $key => $idPri) {

            $preparePri = $wpdb->prepare(
                "SELECT muscle_id as muscle_id
                    FROM {$wpdb->prefix}exercise_primary_option
                    WHERE exercise_id = %d 
                    ",
                $idPri
            );

            $resultPrimary = $wpdb->get_col($preparePri);

            if (!array_intersect($resultPrimary, $muscle_ids)) {
                unset($samePrimary[$key]);
            }
        }
    }



    if ($primaryDatas):
        ?>
        <section class="exc-primary">
            <div class="container">
                <div class="exc-container bd-bot">
                    <div class="muscle-title">
                        <h2>Primary Muscle Groups</h2>
                    </div>
                </div>
            </div>
        </section>
        <?php if (!empty($contentPrimary)): ?>
            <div class="container">
                <div class="exc-container">
                    <div class="muscle-text bd-bot exercise-list-start">
                        <?= $contentPrimary ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    $secondaryIds = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}exercise_secondary_option WHERE exercise_id = " . $exerciseId,
        ARRAY_A
    );

    $arrSecondaryId = array();
    foreach ($secondaryIds as $secondaryId) {
        $arrSecondaryId[] = $secondaryId['muscle_id'];
    }

    $secondaryDatas = [];
    $ids = '';
    if ($arrSecondaryId) {
        $ids = implode(',', $arrSecondaryId);

        $secondaryDatas = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}exercise_muscle_anatomy WHERE id IN ({$ids}) AND active = 1",
            ARRAY_A
        );
    }
    if ($secondaryDatas):
        ?>
        <section class="exc-secondary">
            <div class="container">
                <div class="exc-container bd-bot">
                    <div class="muscle-title">
                        <h2>Secondary Muscle Groups</h2>
                    </div>
                </div>
            </div>
        </section>
        <?php if (!empty($contentSecondary)): ?>
            <div class="container">
                <div class="exc-container">
                    <div class="muscle-text bd-bot exercise-list-start">
                        <?= $contentSecondary ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
    <?php
    $equipmentIds = $wpdb->get_results(
        "SELECT * FROM {$wpdb->prefix}exercise_equipment_option WHERE exercise_id = " . $exerciseId,
        ARRAY_A
    );

    $arrEquipmentId = array();
    foreach ($equipmentIds as $equipmentId) {
        $arrEquipmentId[] = $equipmentId['equipment_id'];
    }

    $equipmentDatas = [];

    $ids = '';
    $sameEquipment = [];

    if ($arrEquipmentId) {
        $ids = implode(',', $arrEquipmentId);

        $equipmentDatas = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}exercise_equipment WHERE id IN ({$ids}) AND active = 1",
            ARRAY_A
        );

        $placeholderss = implode(',', array_fill(0, count($arrEquipmentId), '%d'));

        $var = $wpdb->prepare(
            "SELECT exercise_id
                FROM {$wpdb->prefix}exercise_equipment_option
                WHERE equipment_id IN ($placeholderss)
                AND exercise_id != %d
                GROUP BY exercise_id",
            array_merge($arrEquipmentId, [$exerciseId])
        );

        $sameEquipment = $wpdb->get_results($var, ARRAY_A);
    }


    if ($equipmentDatas):
        ?>
        <section class="exc-equipment">
            <div class="container">
                <div class="exc-container bd-bot">
                    <div class="muscle-title">
                        <h2>Equipment</h2>
                    </div>
                    <div class="equipment-container">
                        <div class="muscle-text exercise-equipment-start">
                            <?= $contentEquipment ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php

    $primaryIds = array_column($samePrimary, 'exercise_id');
    $equipmentIds = array_column($sameEquipment, 'exercise_id');


    $variations = array_intersect($primaryIds, $equipmentIds);

    $i = 0;
    $variationsDatas = array();

    if ($variations) {
        $idsVar = implode(',', $variations);

        $variationsDatas = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}exercise WHERE id IN ({$idsVar}) AND active = 1 LIMIT 10",
            ARRAY_A
        );
    }

    if ($variationsDatas):
        ?>
        <section class="exc-variations">
            <div class="container">
                <div class="exc-container bd-bot">
                    <div class="muscle-title">
                        <h2>Variations</h2>
                        <p class="">Exercises that target the same primary muscle groups and require the same equipment.</p>
                    </div>
                    <div class="muscle-list variations-list">
                        <ul>
                            <?php
                            foreach ($variationsDatas as $variationsData): ?>
                                <li>
                                    <?php if ($variationsData['slug']): ?>
                                        <a href="<?= home_url('/exercise/' . $variationsData['slug']); ?>">
                                            <p class="has-medium-font-size"><?= $variationsData['name'] ?></p>
                                        </a>
                                    <?php else: ?>
                                        <p class="has-medium-font-size"><?= $variationsData['name'] ?>
                                        <?php endif; ?>
                                </li>
                                <?php
                                $i++;
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php

    $alternative = array_diff($primaryIds, $equipmentIds);

    $i = 0;
    $alternativeDatas = array();

    if ($alternative) {
        $idsVar = implode(',', $alternative);

        $alternativeDatas = $wpdb->get_results(
            "SELECT * FROM {$wpdb->prefix}exercise WHERE id IN ({$idsVar}) AND active = 1 LIMIT 10",
            ARRAY_A
        );
    }

    if ($alternativeDatas):
        ?>
        <section class="exc-alternatives">
            <div class="container">
                <div class="exc-container bd-bot">
                    <div class="muscle-title">
                        <h2>Alternatives</h2>
                        <p class="">Exercises that target the same primary muscle groups and require the different
                            equipment.</p>
                    </div>
                    <div class="muscle-list alternatives-list">
                        <ul>
                            <?php
                            foreach ($alternativeDatas as $alternativeData): ?>
                                <li>
                                    <?php if ($alternativeData['slug']): ?>
                                        <a target="_blank" href="<?= home_url('/exercise/' . $alternativeData['slug']); ?>">
                                            <p class="has-medium-font-size"><?= $alternativeData['name'] ?></p>
                                        </a>
                                    <?php else: ?>
                                        <p class="has-medium-font-size"><?= $alternativeData['name'] ?>
                                        <?php endif; ?>
                                </li>
                                <?php
                                $i++;
                            endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>