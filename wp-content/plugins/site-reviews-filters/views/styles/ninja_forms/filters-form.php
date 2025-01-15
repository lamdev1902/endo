<?php defined('WPINC') || die; ?>

<div class="nf-form-wrap ninja-forms-form-wrap">
    <div class="nf-form-layout">
        <form class="{{ class }}" method="get" {{ attributes }}>
            <div class="nf-form-content">
                {{ search_for }}
                {{ sort_by }}
                {{ filter_by }}
            </div>
        </form>
    </div>
</div>


