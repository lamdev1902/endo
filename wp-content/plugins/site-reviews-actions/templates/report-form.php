<?php defined('ABSPATH') || exit; ?>

<div class="glsr-form-wrap">
    <form class="{{ class }}" method="post" enctype="multipart/form-data">
        {{ hidden_fields }}
        <fieldset id="report-review-step-1">
            {{ step_1_fields }}
        </fieldset>
        <fieldset id="report-review-step-2">
            {{ step_2_fields }}
        </fieldset>
        <fieldset id="report-review-step-3">
            {{ step_3_fields }}
        </fieldset>
        {{ response }}
        {{ submit_button }}
    </form>
</div>
