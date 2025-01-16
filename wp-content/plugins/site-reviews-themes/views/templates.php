<?php defined('WPINC') || die; ?>

<script type="text/html" id="tmpl-glsrt-field">
    <# if (data.tag === 'images') { #>
        {{{ data.value }}}
    <# } else { #>
        <div class="glsr-{{ data.custom ? 'custom' : 'review' }}-{{ data.tag }}">
            <# if ('textarea' === data.type) { #>
                <# if (data.tag_label) { #>
                    <div class="glsr-tag-label">{{{ data.tag_label }}}</div>
                <# } #>
                {{{ data.text }}}<# if (data.text !== data.value) { #>&hellip; <a href=""><?= _x('Read more', 'admin-text', 'site-reviews-themes'); ?></a><# } #>
            <# } else { #>
                {{{ data.value }}}
            <# } #>
        </div>
    <# } #>
</script>

<script type="text/html" id="tmpl-glsrt-input-checkbox">
    <# _.each(data.options, function (label, value) { #>
        <label for="checkbox-{{ value }}"><input type="checkbox" id="checkbox-{{ value }}" value="{{ value }}" <# if (~data.value.indexOf(value)) { #>checked<# } #> /> {{{ label }}}</label>
    <# }) #>
</script>

<script type="text/html" id="tmpl-glsrt-input-colorpicker">
    <input type="text" class="glsr-color-picker glsr-input-value"
        data-alpha-enabled="true"
        data-tippy-content="{{ data.tooltip }}"
        data-tippy-allowHTML="true"
        value="{{ data.value }}"
    >
</script>

<script type="text/html" id="tmpl-glsrt-input-boxshadow">
    <div class="glsrt-boxshadow glsrt-multifield">
        <# _.each(data.value.split('|'), (value, i) => { #>
            <# if (i < 4) { #>
                <input type="number"
                    data-linked
                    data-tippy-content="{{ data.tooltips[i] }}"
                    placeholder="0"
                    value="{{ value }}"
                >
            <# } else { #>
                <input type="text" class="glsr-color-picker glsr-input-value"
                    data-alpha-enabled="true"
                    data-linked
                    data-tippy-content="{{ data.tooltips[i] }}"
                    value="{{ value }}"
                >
            <# } #>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-input-dimensions">
    <div class="glsrt-dimensions glsrt-multifield">
        <# _.each(data.value.split('|'), (value, i) => { #>
            <# if (i < 4) { #>
                <input type="number"
                    data-linked
                    data-tippy-content="{{ data.tooltips[i] }}"
                    min="0"
                    placeholder="0"
                    value="{{ value }}"
                >
            <# } else if (i === 4) { #>
                <select data-linked data-tippy-content="{{ data.tooltips[i] }}">
                    <option value="%" <# if (value === '%') { #>selected<# } #>>%</option>
                    <option value="px" <# if (value === 'px') { #>selected<# } #>>px</option>
                    <option value="em" <# if (value === 'em') { #>selected<# } #>>em</option>
                    <option value="rem" <# if (value === 'rem') { #>selected<# } #>>rem</option>
                </select>
            <# } else { #>
                <button type="button"
                    class="components-button dashicons-admin-links dashicons-before has-icon is-small <# if (+value) { #>is-pressed<# } #>"
                    data-tippy-content="<?= _x('Link values together', 'admin-text', 'site-reviews-themes'); ?>"
                >
                    <span class="screen-reader-text"><?= _x('Click to link the values together', 'admin-text', 'site-reviews-themes'); ?></span>
                </button>
            <# } #>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-input-excerpt">
    <div class="glsrt-excerpt glsrt-multifield">
        <# _.each(data.value.split('|'), (value, i) => { #>
            <# if (i === 0) { #>
                <input type="number"
                    data-linked
                    data-tippy-content="{{ data.tooltip }}"
                    min="0"
                    value="{{ value }}"
                >
            <# } else { #>
                <select data-linked data-tippy-content="{{ data.tooltip }}">
                    <option value="chars" <# if (value === 'chars') { #>selected<# } #>><?= _x('characters', 'admin-text', 'site-reviews-themes'); ?></option>
                    <option value="words" <# if (value === 'words') { #>selected<# } #>><?= _x('words', 'admin-text', 'site-reviews-themes'); ?></option>
                </select>
            <# } #>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-input-number">
    <div class="glsrt-input">
        <input type="number" class="glsr-input-value small-text"
            data-tippy-content="{{ data.tooltip }}"
            data-tippy-allowHTML="true"
            min="0"
            value="{{ data.value }}"
        >
        <span>{{{ data.after }}}</span>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-input-range">
    <div class="glsrt-input">
        <input type="range" style="flex:1;"
            data-tippy-content="{{ data.tooltip }}"
            data-tippy-allowHTML="true"
            max="{{ data.max || 0 }}"
            min="{{ data.min || 0 }}"
            step="{{ data.step || 1 }}"
            value="{{ data.value }}"
        >
        <input type="number" style="margin-left:12px;width:4.5em;"
            data-tippy-content="{{ data.tooltip }}"
            data-tippy-allowHTML="true"
            max="{{ data.max || 0 }}"
            min="{{ data.min || 0 }}"
            step="{{ data.step || 1 }}"
            value="{{ data.value }}"
        >
        <span>{{{ data.after }}}</span>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-input-ratingcolors">
    <div class="glsrt-ratingcolors">
        <# _.each(data.value.split('|'), (value, i) => { #>
            <# if (i < 6) { #>
                <input type="text" class="glsr-color-picker glsr-input-value"
                    data-alpha-enabled="true"
                    data-linked
                    data-tippy-content="{{ data.tooltips[i] }}"
                    value="{{ value }}"
                >
            <# } else { #>
                <button type="button"
                    class="components-button dashicons-admin-links dashicons-before has-icon is-small <# if (+value) { #>is-pressed<# } #>"
                    data-tippy-content="<?= _x('Link values together', 'admin-text', 'site-reviews-themes'); ?>"
                >
                    <span class="screen-reader-text"><?= _x('Click to link the values together', 'admin-text', 'site-reviews-themes'); ?></span>
                </button>
            <# } #>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-input-text">
    <input type="text" class="glsr-input-value"
        data-tippy-content="{{ data.tooltip }}"
        data-tippy-allowHTML="true"
        value="{{ data.value }}"
    >
</script>

<script type="text/html" id="tmpl-glsrt-input-typography">
    <div class="glsrt-typography glsrt-multifield">
        <# _.each(data.value.split('|'), (value, i) => { #>
            <# if (i < 2) { #>
                <input type="number"
                    data-linked
                    data-tippy-content="{{ data.tooltips[i] }}"
                    placeholder="0"
                    value="{{ value }}"
                >
            <# } else if (i === 2) { #>
                <select data-linked data-tippy-content="{{ data.tooltips[i] }}">
                    <option value="ch" <# if (value === 'ch') { #>selected<# } #>>ch</option>
                    <option value="em" <# if (value === 'em') { #>selected<# } #>>em</option>
                    <option value="ex" <# if (value === 'ex') { #>selected<# } #>>ex</option>
                    <option value="pt" <# if (value === 'pt') { #>selected<# } #>>pt</option>
                    <option value="px" <# if (value === 'px') { #>selected<# } #>>px</option>
                    <option value="rem" <# if (value === 'rem') { #>selected<# } #>>rem</option>
                </select>
            <# } else { #>
                <input type="text" class="glsr-color-picker glsr-input-value"
                    data-alpha-enabled="true"
                    data-linked
                    data-tippy-content="{{ data.tooltips[i] }}"
                    value="{{ value }}"
                >
            <# } #>
        <# }) #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-nav">
    <div class="components-panel__header edit-post-sidebar__panel-tabs" tabindex="-1">
        <div class="glsr-panel-tabs" role="tablist" aria-orientation="horizontal">
        </div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-preview">
    <# if (!data.has_reviews) { #>
        <div class="glsrt-notice">
            <?= _x('When you have reviews to display, they will replace the review placeholders below.', 'admin-text', 'site-reviews-themes'); ?>
        </div>
    <# } #>
    <div class="glsrt-preview glsr" data-theme>
        <# if ('carousel' === _.get(data.layout, 'display_as')) { #>
            <# if ('splide' === data.swiper) { #>
                <div class="splide gl-splide gl-carousel">
                    <div class="splide__track">
                        <div class="splide__list gl-reviews gl-swiper-wrapper"></div>
                    </div>
                </div>
            <# } else { #>
                <div class="gl-swiper gl-carousel">
                    <div class="gl-reviews gl-swiper-wrapper"></div>
                    <div class="gl-swiper-arrows">
                        <div class="gl-swiper-arrow gl-swiper-prev">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="currentColor"><path d="M14.447 21.004l7.214 7.057c.24.246.533.363.881.363.708 0 1.277-.559 1.277-1.258 0-.354-.15-.67-.402-.922l-6.414-6.246 6.414-6.24c.254-.254.402-.574.402-.919 0-.702-.569-1.258-1.277-1.258-.351 0-.641.117-.881.357l-7.214 7.06c-.31.3-.444.62-.447 1.003s.135.698.447 1.004z"/></svg>
                        </div>
                        <div class="gl-swiper-arrow gl-swiper-next">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40" fill="currentColor"><path d="M14.447 21.004l7.214 7.057c.24.246.533.363.881.363.708 0 1.277-.559 1.277-1.258 0-.354-.15-.67-.402-.922l-6.414-6.246 6.414-6.24c.254-.254.402-.574.402-.919 0-.702-.569-1.258-1.277-1.258-.351 0-.641.117-.881.357l-7.214 7.06c-.31.3-.444.62-.447 1.003s.135.698.447 1.004z"/></svg>
                        </div>
                    </div>
                    <div class="gl-swiper-pagination"></div>
                </div>
            <# } #>
        <# } else if ('grid' === _.get(data.layout, 'display_as')) { #>
            <div class="gl-reviews gl-display-grid gl-grid-{{ _.get(data.layout, 'max_columns') }}"></div>
        <# } else { #>
            <div class="gl-reviews gl-display-list"></div>
        <# } #>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-preview-placeholder">
    <div class="srt-space-y">
        <div class="srt-flex srt-space-x">
            <svg class="srt-avatar" viewBox="0 0 50 50">
                <path fill="currentColor" d="M50 0v50h-4.997a22.284 22.284 0 00-2.743-6.387 20.468 20.468 0 00-4.516-5.065 20.808 20.808 0 00-5.892-3.316c-2.157-.791-4.441-1.186-6.854-1.186-2.412 0-4.692.395-6.837 1.186-2.146.79-4.1 1.896-5.86 3.316a20.903 20.903 0 00-4.517 5.065 21.93 21.93 0 00-2.626 5.898l-.132.49H0V0h50zM25 9.687c-1.708 0-3.256.45-4.641 1.353-1.386.902-2.492 2.099-3.317 3.59-.826 1.49-1.23 3.138-1.21 4.943 0 1.899.408 3.604 1.224 5.114.816 1.51 1.917 2.706 3.303 3.59 1.385.883 2.933 1.334 4.641 1.353 1.708 0 3.256-.437 4.641-1.31 1.386-.875 2.487-2.076 3.303-3.605.816-1.529 1.225-3.243 1.225-5.142 0-1.805-.409-3.452-1.225-4.943-.816-1.491-1.917-2.688-3.303-3.59-1.385-.902-2.933-1.353-4.641-1.353z"/>
            </svg>
            <div class="srt-flex-grow srt-space-y">
                <svg class="srt-rating" viewBox="0 0 120 20" fill="currentColor">
                    <path d="M16.9281398,19.8163587 C17.1325762,19.6582232 17.257876,19.4506703 17.304039,19.1937001 C17.3502021,18.9367298 17.3139311,18.6303423 17.1952261,18.2745373 L15.2563774,12.5124739 L20.20242,8.97419139 C20.5057772,8.75016605 20.7184571,8.52119898 20.8404595,8.28729018 C20.9624618,8.05338137 20.9805973,7.81453083 20.8948659,7.57073855 C20.8157292,7.32694627 20.6574559,7.14410207 20.4200458,7.02220593 C20.1826358,6.90030979 19.8792785,6.84265621 19.509974,6.84924519 L13.4362337,6.88877907 L11.5963059,1.09706525 C11.4776009,0.734671321 11.3259223,0.461228632 11.14127,0.276737179 C10.9566177,0.0922457265 10.7323971,0 10.4686082,0 C10.211414,0 9.99049076,0.0922457265 9.8058385,0.276737179 C9.62118625,0.461228632 9.46950761,0.734671321 9.35080259,1.09706525 L7.51087476,6.88877907 L1.44702658,6.84924519 C1.07112734,6.84265621 0.766121384,6.90030979 0.532008703,7.02220593 C0.297896022,7.14410207 0.137973979,7.32365178 0.0522425745,7.56085508 C-0.0334888298,7.81123634 -0.0137046596,8.05338137 0.111595085,8.28729018 C0.23689483,8.52119898 0.451223341,8.75016605 0.754580618,8.97419139 L5.70062318,12.5124739 L3.76177449,18.2745373 C3.64306947,18.6303423 3.60514981,18.9367298 3.64801552,19.1937001 C3.69088122,19.4506703 3.81453228,19.6582232 4.01896871,19.8163587 C4.22999986,19.9744943 4.46576122,20.0305006 4.72625279,19.9843777 C4.98674437,19.9382549 5.26866879,19.8031808 5.57202607,19.5791554 L10.4686082,15.9815721 L15.3750824,19.5791554 C15.6850344,19.8031808 15.9702562,19.9382549 16.2307478,19.9843777 C16.4912394,20.0305006 16.7237034,19.9744943 16.9281398,19.8163587 Z M41.6911554,19.8163587 C41.8955918,19.6582232 42.0208915,19.4506703 42.0670546,19.1937001 C42.1132177,18.9367298 42.0769467,18.6303423 41.9582417,18.2745373 L40.019393,12.5124739 L44.9654355,8.97419139 C45.2687928,8.75016605 45.4814726,8.52119898 45.603475,8.28729018 C45.7254774,8.05338137 45.7436129,7.81453083 45.6578815,7.57073855 C45.5787448,7.32694627 45.4204714,7.14410207 45.1830614,7.02220593 C44.9456514,6.90030979 44.6422941,6.84265621 44.2729896,6.84924519 L38.1992493,6.88877907 L36.3593215,1.09706525 C36.2406165,0.734671321 36.0889378,0.461228632 35.9042856,0.276737179 C35.7196333,0.0922457265 35.4954127,0 35.2316238,0 C34.9744296,0 34.7535063,0.0922457265 34.5688541,0.276737179 C34.3842018,0.461228632 34.2325232,0.734671321 34.1138182,1.09706525 L32.2738903,6.88877907 L26.2100421,6.84924519 C25.8341429,6.84265621 25.5291369,6.90030979 25.2950243,7.02220593 C25.0609116,7.14410207 24.9009895,7.32365178 24.8152581,7.56085508 C24.7295267,7.81123634 24.7493109,8.05338137 24.8746106,8.28729018 C24.9999104,8.52119898 25.2142389,8.75016605 25.5175962,8.97419139 L30.4636387,12.5124739 L28.5247901,18.2745373 C28.406085,18.6303423 28.3681654,18.9367298 28.4110311,19.1937001 C28.4538968,19.4506703 28.5775478,19.6582232 28.7819843,19.8163587 C28.9930154,19.9744943 29.2287768,20.0305006 29.4892684,19.9843777 C29.7497599,19.9382549 30.0316844,19.8031808 30.3350416,19.5791554 L35.2316238,15.9815721 L40.138098,19.5791554 C40.44805,19.8031808 40.7332718,19.9382549 40.9937634,19.9843777 C41.2542549,20.0305006 41.4867189,19.9744943 41.6911554,19.8163587 Z M66.4541709,19.8163587 C66.6586073,19.6582232 66.7839071,19.4506703 66.8300702,19.1937001 C66.8762332,18.9367298 66.8399622,18.6303423 66.7212572,18.2745373 L64.7824085,12.5124739 L69.7284511,8.97419139 C70.0318084,8.75016605 70.2444882,8.52119898 70.3664906,8.28729018 C70.488493,8.05338137 70.5066285,7.81453083 70.4208971,7.57073855 C70.3417604,7.32694627 70.183487,7.14410207 69.946077,7.02220593 C69.7086669,6.90030979 69.4053096,6.84265621 69.0360051,6.84924519 L62.9622649,6.88877907 L61.122337,1.09706525 C61.003632,0.734671321 60.8519534,0.461228632 60.6673011,0.276737179 C60.4826489,0.0922457265 60.2584283,0 59.9946393,0 C59.7374451,0 59.5165219,0.0922457265 59.3318696,0.276737179 C59.1472174,0.461228632 58.9955387,0.734671321 58.8768337,1.09706525 L57.0369059,6.88877907 L50.9730577,6.84924519 C50.5971585,6.84265621 50.2921525,6.90030979 50.0580398,7.02220593 C49.8239271,7.14410207 49.6640051,7.32365178 49.5782737,7.56085508 C49.4925423,7.81123634 49.5123265,8.05338137 49.6376262,8.28729018 C49.762926,8.52119898 49.9772545,8.75016605 50.2806117,8.97419139 L55.2266543,12.5124739 L53.2878056,18.2745373 C53.1691006,18.6303423 53.1311809,18.9367298 53.1740466,19.1937001 C53.2169123,19.4506703 53.3405634,19.6582232 53.5449998,19.8163587 C53.756031,19.9744943 53.9917923,20.0305006 54.2522839,19.9843777 C54.5127755,19.9382549 54.7946999,19.8031808 55.0980572,19.5791554 L59.9946393,15.9815721 L64.9011136,19.5791554 C65.2110656,19.8031808 65.4962873,19.9382549 65.7567789,19.9843777 C66.0172705,20.0305006 66.2497345,19.9744943 66.4541709,19.8163587 Z M91.2171865,19.8163587 C91.4216229,19.6582232 91.5469227,19.4506703 91.5930857,19.1937001 C91.6392488,18.9367298 91.6029778,18.6303423 91.4842728,18.2745373 L89.5454241,12.5124739 L94.4914667,8.97419139 C94.7948239,8.75016605 95.0075038,8.52119898 95.1295061,8.28729018 C95.2515085,8.05338137 95.269644,7.81453083 95.1839126,7.57073855 C95.1047759,7.32694627 94.9465026,7.14410207 94.7090925,7.02220593 C94.4716825,6.90030979 94.1683252,6.84265621 93.7990207,6.84924519 L87.7252804,6.88877907 L85.8853526,1.09706525 C85.7666476,0.734671321 85.6149689,0.461228632 85.4303167,0.276737179 C85.2456644,0.0922457265 85.0214438,0 84.7576549,0 C84.5004607,0 84.2795375,0.0922457265 84.0948852,0.276737179 C83.9102329,0.461228632 83.7585543,0.734671321 83.6398493,1.09706525 L81.7999214,6.88877907 L75.7360733,6.84924519 C75.360174,6.84265621 75.0551681,6.90030979 74.8210554,7.02220593 C74.5869427,7.14410207 74.4270207,7.32365178 74.3412893,7.56085508 C74.2555579,7.81123634 74.275342,8.05338137 74.4006418,8.28729018 C74.5259415,8.52119898 74.74027,8.75016605 75.0436273,8.97419139 L79.9896699,12.5124739 L78.0508212,18.2745373 C77.9321162,18.6303423 77.8941965,18.9367298 77.9370622,19.1937001 C77.9799279,19.4506703 78.103579,19.6582232 78.3080154,19.8163587 C78.5190465,19.9744943 78.7548079,20.0305006 79.0152995,19.9843777 C79.2757911,19.9382549 79.5577155,19.8031808 79.8610728,19.5791554 L84.7576549,15.9815721 L89.6641291,19.5791554 C89.9740811,19.8031808 90.2593029,19.9382549 90.5197945,19.9843777 C90.7802861,20.0305006 91.0127501,19.9744943 91.2171865,19.8163587 Z M115.980202,19.8163587 C116.184638,19.6582232 116.309938,19.4506703 116.356101,19.1937001 C116.402264,18.9367298 116.365993,18.6303423 116.247288,18.2745373 L114.30844,12.5124739 L119.254482,8.97419139 C119.557839,8.75016605 119.770519,8.52119898 119.892522,8.28729018 C120.014524,8.05338137 120.03266,7.81453083 119.946928,7.57073855 C119.867791,7.32694627 119.709518,7.14410207 119.472108,7.02220593 C119.234698,6.90030979 118.931341,6.84265621 118.562036,6.84924519 L112.488296,6.88877907 L110.648368,1.09706525 C110.529663,0.734671321 110.377985,0.461228632 110.193332,0.276737179 C110.00868,0.0922457265 109.784459,0 109.52067,0 C109.263476,0 109.042553,0.0922457265 108.857901,0.276737179 C108.673249,0.461228632 108.52157,0.734671321 108.402865,1.09706525 L106.562937,6.88877907 L100.499089,6.84924519 C100.12319,6.84265621 99.8181836,6.90030979 99.584071,7.02220593 C99.3499583,7.14410207 99.1900362,7.32365178 99.1043048,7.56085508 C99.0185734,7.81123634 99.0383576,8.05338137 99.1636573,8.28729018 C99.2889571,8.52119898 99.5032856,8.75016605 99.8066429,8.97419139 L104.752685,12.5124739 L102.813837,18.2745373 C102.695132,18.6303423 102.657212,18.9367298 102.700078,19.1937001 C102.742943,19.4506703 102.866595,19.6582232 103.071031,19.8163587 C103.282062,19.9744943 103.517823,20.0305006 103.778315,19.9843777 C104.038807,19.9382549 104.320731,19.8031808 104.624088,19.5791554 L109.52067,15.9815721 L114.427145,19.5791554 C114.737097,19.8031808 115.022318,19.9382549 115.28281,19.9843777 C115.543302,20.0305006 115.775766,19.9744943 115.980202,19.8163587 Z"/>
                </svg>
                <div class="srt-text srt-text--small"></div>
            </div>
        </div>
        <div class="srt-text"></div>
        <div class="srt-text srt-text--large"></div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-select">
    <select data-tippy-content='{{ data.tooltip }}' data-tippy-allowHTML="true">
        <# _.each(data.options, function (optLabel, optValue) { #>
            <# if (_.isObject(optLabel)) { #>
                <optgroup label="{{ optValue }}">
                    <# _.each(optLabel, function (childLabel, childValue) { #>
                        <option value="{{ childValue }}" <# if (data.value == childValue) { #>selected<# } #>>{{ childLabel }}</option>
                    <# }) #>
                </optgroup>
            <# } else { #>
                <option value="{{ optValue }}" <# if (data.value == optValue) { #>selected<# } #>>{{ optLabel }}</option>
            <# } #>
        <# }) #>
    </select>
</script>

<script type="text/html" id="tmpl-glsrt-setting">
    <# if (data.label) { #><label>{{{ data.label }}}</label><# } #>
    <div class="glsr-input wp-clearfix"></div>
</script>

<script type="text/html" id="tmpl-glsrt-setting-panel">
    <div class="components-panel__body" data-controls="{{ data.section }}">
        <div class="components-panel__body-title">
            <button aria-expanded="false" class="components-button components-panel__body-toggle dashicons-before {{ data.dashicon }}" type="button">
                <span aria-hidden="true">
                    <svg aria-hidden="true" class="components-panel__arrow" focusable="false" height="24" role="img" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg">
                        <# if (data.opened) { #>
                            <path d="M6.5 12.4L12 8l5.5 4.4-.9 1.2L12 10l-4.5 3.6-1-1.2z"/>
                        <# } else { #>
                            <path d="M17.5 11.6L12 16l-5.5-4.4.9-1.2L12 14l4.5-3.6 1 1.2z"/>
                        <# } #>
                    </svg>
                </span>
                <span class="components-panel__label">{{{ data.label }}}</span>
            </button>
        </div>
        <div class="glsrt-fields"></div>
    </div>
</script>

<script type="text/html" id="tmpl-glsrt-toolbar">
    <div class="glsrt-toolbar-group">
        <button type="button" class="components-button has-icon"
            aria-pressed="false"
            aria-disabled="{{ _.isEmpty(data.model) ? true : false }}"
            data-action="wrap"
            data-tippy-content="<?= _x('Insert the selected field or container into a parent container', 'admin-text', 'site-reviews-themes'); ?>"
            <# if (_.isEmpty(data.model)) { #>disabled<# } #>>
            <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" d="M3.49854289,16.4993862 L3.4992285,19.2180574 C3.4992285,19.6638168 3.54564131,19.8254599 3.63279485,19.9884229 C3.7199484,20.1513858 3.84784269,20.2792801 4.01080565,20.3664336 C4.17376861,20.4535872 4.33541173,20.5 4.7811711,20.5 L4.7811711,20.5 L7.49954289,20.4993862 L7.49954289,21.9993862 L5.2048565,22 L4.99123592,21.9984571 L4.6157436,21.9861896 C4.10044813,21.9586822 3.78947879,21.8904243 3.52870008,21.7844788 L3.36059179,21.7082987 L3.27894287,21.6660841 C2.87153546,21.4482003 2.55179975,21.1284645 2.33391588,20.7210571 L2.29170133,20.6394082 L2.21552122,20.4712999 C2.10957567,20.2105212 2.04131776,19.8995519 2.0138104,19.3842564 L2.00154289,19.0087641 L2.00154289,16.4993862 L3.49854289,16.4993862 Z M14.9995429,20.4993862 L14.9995429,21.9993862 L8.99954289,21.9993862 L8.99954289,20.4993862 L14.9995429,20.4993862 Z M21.9995429,16.4993862 L22,18.7951435 L21.9984571,19.0087641 L21.9861896,19.3842564 C21.9586822,19.8995519 21.8904243,20.2105212 21.7844788,20.4712999 L21.7082987,20.6394082 L21.6660841,20.7210571 C21.4482003,21.1284645 21.1284645,21.4482003 20.7210571,21.6660841 L20.6394082,21.7082987 L20.4712999,21.7844788 C20.2105212,21.8904243 19.8995519,21.9586822 19.3842564,21.9861896 L19.0087641,21.9984571 L18.9042421,21.9996138 L18.9042421,21.9996138 L16.4995429,21.9993862 L16.4995429,20.4993862 L19.2188289,20.5 C19.6088683,20.5 19.7813813,20.4644652 19.9275876,20.3971954 L19.9891944,20.3664336 C20.1521573,20.2792801 20.2800516,20.1513858 20.3672051,19.9884229 C20.4543587,19.8254599 20.5007715,19.6638168 20.5007715,19.2180574 L20.5007715,19.2180574 L20.5005429,16.4993862 L21.9995429,16.4993862 Z M18,6 L18,18 L6,18 L6,6 L18,6 Z M16.5,7.5 L7.5,7.5 L7.5,16.5 L16.5,16.5 L16.5,7.5 Z M21.9995429,8.99938625 L21.9995429,14.9993862 L20.5005429,14.9993862 L20.5005429,8.99938625 L21.9995429,8.99938625 Z M3.49854289,8.99938625 L3.49854289,14.9993862 L2.00154289,14.9993862 L2.00154289,8.99938625 L3.49854289,8.99938625 Z M19.0087641,2.00154289 L19.3842564,2.0138104 C19.8995519,2.04131776 20.2105212,2.10957567 20.4712999,2.21552122 L20.6394082,2.29170133 L20.7210571,2.33391588 C21.1284645,2.55179975 21.4482003,2.87153546 21.6660841,3.27894287 L21.7082987,3.36059179 L21.7844788,3.52870008 C21.8904243,3.78947879 21.9586822,4.10044813 21.9861896,4.6157436 L21.9984571,4.99123592 L21.9996138,5.09575786 L21.9995429,7.49938625 L20.5005429,7.49938625 L20.5007715,4.7819426 C20.5007715,4.39190315 20.4652367,4.21939021 20.3979669,4.07318388 L20.3672051,4.01157715 C20.2800516,3.84861419 20.1521573,3.7207199 19.9891944,3.63356635 C19.8262314,3.54641281 19.6645883,3.5 19.2188289,3.5 L19.2188289,3.5 L16.4995429,3.49938625 L16.4995429,2.00138625 L19.0087641,2.00154289 Z M7.49954289,2.00038625 L7.49954289,3.49938625 L4.7811711,3.5 C4.39113165,3.5 4.21861871,3.5355348 4.07241238,3.60280457 L4.01080565,3.63356635 C3.84784269,3.7207199 3.7199484,3.84861419 3.63279485,4.01157715 C3.54564131,4.17454011 3.4992285,4.33618323 3.4992285,4.7819426 L3.4992285,4.7819426 L3.49854289,7.49938625 L2.00154289,7.49938625 L2.00154289,4.99123592 L2.0138104,4.6157436 C2.04131776,4.10044813 2.10957567,3.78947879 2.21552122,3.52870008 L2.29170133,3.36059179 C2.30531737,3.33324919 2.31939029,3.30610336 2.33391588,3.27894287 C2.55179975,2.87153546 2.87153546,2.55179975 3.27894287,2.33391588 L3.36059179,2.29170133 L3.52870008,2.21552122 C3.78947879,2.10957567 4.10044813,2.04131776 4.6157436,2.0138104 L4.99123592,2.00154289 L5.09575786,2.00038625 L5.09575786,2.00038625 L7.49954289,2.00038625 Z M8.99954289,2.00038625 L14.9995429,2.00138625 L14.9995429,3.49938625 L8.99954289,3.49938625 L8.99954289,2.00038625 Z"/>
            </svg>
        </button>
    </div>
    <div class="glsrt-toolbar-group">
        <button type="button" class="components-button has-icon" 
            aria-pressed="false" 
            aria-disabled="{{ _.isEmpty(data.model) ? true : false }}"
            data-action="remove" 
            data-tippy-content="<?= _x('Remove the field', 'admin-text', 'site-reviews-themes'); ?>"
            <# if (_.isEmpty(data.model)) { #>disabled<# } #>>
            <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                <g fill="none" stroke-width="1.25" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="4" y1="7" x2="20" y2="7" />
                    <line x1="10" y1="11" x2="10" y2="17" />
                    <line x1="14" y1="11" x2="14" y2="17" />
                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                </g>
            </svg>
         </button>
    </div>
    <# if (!_.isEmpty(data.model) && data.is_container) { #>
        <div class="glsrt-toolbar-group">
            <button  type="button" class="components-button has-icon"
                aria-pressed="{{ data.model.direction === 'row' }}"
                data-action="direction"
                data-tippy-content="<?= _x('Change the direction that the fields flow in the container', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="m20 16 4 4-4 4v-3.2498831h-16v-1.5010027h16zm-2-12v12h-6v-12zm-8 0v12h-6v-12zm-1.5 1.5h-3v9h3z"/>
                    <# } else { #>
                        <path fill="currentColor" d="m16 20 4 4 4-4h-3.2498831v-16h-1.5010027v16zm-12-2h12v-6h-12zm12-14v6h-12v-6zm-1.5 1.5h-9v3h9z"/>
                    <# } #>
                </svg>
            </button>
        </div>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if (data.model.wrap) { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.wrap }}"
                data-action="flex-wrap"
                data-tippy-content="<# if ('row' === data.model.direction) { #><?= _x('Allow the fields to flow onto multiple rows', 'admin-text', 'site-reviews-themes'); ?><# } else { #><?= _x('Allow the fields to flow onto multiple columns', 'admin-text', 'site-reviews-themes'); ?><# } #>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="m12 13.5v7.5h-9v-7.5zm6.5-7.5c3.6 0 5 2.4 5 6s-1.4 5.5-5 5.5h-.5v3.25l-4-4 4-4v3.25h.5c2.5 0 3.5-1.5 3.5-4s-1-4.5-3.5-4.5h-3.5v-1.5zm-6.5-3v7.5h-9v-7.5zm-1.5 1.5h-6v4.5h6z"/>
                    <# } else { #>
                        <path fill="currentColor" d="m13.5 12h7.5v9h-7.5zm-7.5-6.5c0-3.6 2.4-5 6-5s5.5 1.4 5.5 5v.5h3.25l-4 4-4-4h3.25v-.5c0-2.5-1.5-3.5-4-3.5s-4.5 1-4.5 3.5v3.5h-1.5zm-3 6.5h7.5v9h-7.5zm1.5 1.5v6h4.5v-6z"/>
                    <# } #>
                </svg>
            </button>
        </div>
        <div class="glsrt-toolbar-group">
            <button  type="button" class="components-button has-icon <# if (data.model.align === 'start') { #>is-pressed<# } #>"
                aria-pressed="{{ data.model.align === 'start' }}"
                data-action="align"
                data-value="start"
                <# if ('row' === data.model.direction) { #>
                    data-tippy-content="<?= _x('Align items to the top of the container', 'admin-text', 'site-reviews-themes'); ?>">
                <# } else { #>
                    data-tippy-content="<?= _x('Align items to the left of the container', 'admin-text', 'site-reviews-themes'); ?>">
                <# } #>
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="M15,9 L9,9 L9,20 L15,20 L15,9 Z M4,4 L4,5.5 L20,5.5 L20,4 L4,4 Z"/>
                    <# } else { #>
                        <path fill="currentColor" d="M9 9v6h11V9H9zM4 20h1.5V4H4v16z" />
                    <# } #>
                </svg>
            </button>
            <button type="button" class="components-button has-icon <# if (data.model.align === 'center') { #>is-pressed<# } #>"
                aria-pressed="{{ data.model.align === 'center' }}"
                data-action="align"
                data-value="center"
                data-tippy-content="<?= _x('Align items to the middle of the container', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <polygon fill="currentColor" points="15 20 15 12.8 20 12.8 20 11.2 15 11.2 15 4 9 4 9 11.2 4 11.2 4 12.8 9 12.8 9 20"/>
                    <# } else { #>
                        <path fill="currentColor" d="M20 9h-7.2V4h-1.6v5H4v6h7.2v5h1.6v-5H20z" />
                    <# } #>
                </svg>
            </button>
            <button type="button" class="components-button has-icon <# if (data.model.align === 'end') { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.align === 'end' }}"
                data-action="align"
                data-value="end"
                <# if ('row' === data.model.direction) { #>
                    data-tippy-content="<?= _x('Align items to the bottom of the container', 'admin-text', 'site-reviews-themes'); ?>">
                <# } else { #>
                    data-tippy-content="<?= _x('Align items to the right of the container', 'admin-text', 'site-reviews-themes'); ?>">
                <# } #>
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="M9,4 L9,15 L15,15 L15,4 L9,4 Z M20,18.5 L4,18.5 L4,20 L20,20 L20,18.5 Z"/>
                    <# } else { #>
                        <path fill="currentColor" d="M4 15h11V9H4v6zM18.5 4v16H20V4h-1.5z" />
                    <# } #>
                </svg>
            </button>
        </div>
    <# } #>
    <# if (!_.isEmpty(data.model) && !data.is_root) { #>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if (data.model.flex === 'shrink') { #>is-pressed<# } #>"
                aria-pressed="{{ data.model.flex === 'shrink' }}"
                data-action="flex"
                data-value="shrink"
                data-tippy-content="<?= _x('Shrink if needed', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="M12,10.25 L6,4.25 L11,4.25 L11,1.25 L13,1.25 L13,4.25 L18,4.25 L12,10.25 Z M3,12.75 L3,11.25 L21,11.25 L21,12.75 L3,12.75 Z M13,22.75 L11,22.75 L11,19.75 L6,19.75 L12,13.75 L18,19.75 L13,19.75 L13,22.75 Z"/>
                    <# } else { #>
                        <path fill="currentColor" d="M10.25,12 L4.25,18 L4.25,13 L1.25,13 L1.25,11 L4.25,11 L4.25,6 L10.25,12 Z M12.75,21 L11.25,21 L11.25,3 L12.75,3 L12.75,21 Z M22.75,11 L22.75,13 L19.75,13 L19.75,18 L13.75,12 L19.75,6 L19.75,11 L22.75,11 Z"/>
                    <# } #>
                </svg>
            </button>
            <button type="button" class="components-button has-icon <# if (data.model.flex === 'grow') { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.flex === 'grow' }}" 
                data-action="flex" 
                data-value="grow"
                data-tippy-content="<?= _x('Grow if possible', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="M21,22 L21,20.5 L3,20.5 L3,22 L21,22 Z M21,3.5 L3,3.5 L3,2 L21,2 L21,3.5 Z M11,9.75 L6,9.75 L12,3.75 L18,9.75 L13,9.75 L13,14.25 L18,14.25 L12,20.25 L6,14.25 L11,14.25 L11,9.75 Z"/>
                    <# } else { #>
                        <path fill="currentColor" d="M22,3 L20.5,3 L20.5,21 L22,21 L22,3 Z M3.5,3 L3.5,21 L2,21 L2,3 L3.5,3 Z M9.75,13 L9.75,18 L3.75,12 L9.75,6 L9.75,11 L14.25,11 L14.25,6 L20.25,12 L14.25,18 L14.25,13 L9.75,13 Z"/>
                    <# } #>
                </svg>
            </button>
            <button type="button" class="components-button has-icon <# if (data.model.flex === 'none') { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.flex === 'none' }}" 
                data-action="flex" 
                data-value="none"
                data-tippy-content="<?= _x('Do not grow or shrink', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <# if ('row' === data.model.direction) { #>
                        <path fill="currentColor" d="M21,20.5 L21,22 L3,22 L3,20.5 L21,20.5 Z M16.3918919,5.5 L18.5,7.59395973 L14.1081081,11.9563758 L18.4121622,16.2315436 L16.3918919,18.3255034 L12.0878378,14.0503356 L7.60810811,18.5 L5.5,16.4060403 L9.97972973,11.9563758 L5.58783784,7.59395973 L7.69594595,5.58724832 L12,9.86241611 L16.3918919,5.5 Z M21,2 L21,3.5 L3,3.5 L3,2 L21,2 Z"/>
                    <# } else { #>
                        <path fill="currentColor" d="M2,21 L3.5,21 L3.5,3 L2,3 L2,21 Z M22,3 L22,21 L20.5,21 L20.5,3 L22,3 Z M16.3918919,5.5 L18.5,7.59395973 L14.1081081,11.9563758 L18.4121622,16.2315436 L16.3918919,18.3255034 L12.0878378,14.0503356 L7.60810811,18.5 L5.5,16.4060403 L9.97972973,11.9563758 L5.58783784,7.59395973 L7.69594595,5.58724832 L12,9.86241611 L16.3918919,5.5 Z"/>
                    <# } #>
                </svg>
            </button>
        </div>
    <# } #>
    <# if (!_.isEmpty(data.model) && !data.is_container) { #>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if ('large' === data.model.text) { #>is-pressed<# } #>" 
                data-action="text" 
                data-value="large" 
                aria-pressed="{{ 'large' === data.model.text }}" 
                data-tippy-content="<?= _x('Use the Large Text setting in this field', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m6.2 5.2v13.4l5.8-4.8 5.8 4.8v-13.4z"/>
                </svg>
            </button>
            <button type="button" class="components-button has-icon <# if ('normal' === data.model.text) { #>is-pressed<# } #>" 
                data-action="text" 
                data-value="normal" 
                aria-pressed="{{ 'normal' === data.model.text }}" 
                data-tippy-content="<?= _x('Use the Normal Text setting in this field', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m18.3 4h-8.4v-.1l-.9.2c-2.3.4-4 2.4-4 4.8s1.7 4.4 4 4.8l.7.1v6.2h1.5v-14.5h2.9v14.5h1.5v-14.5h2.7z"/>
                </svg>
            </button>
            <button type="button" class="components-button has-icon <# if ('small' === data.model.text) { #>is-pressed<# } #>" 
                data-action="text" 
                data-value="small" 
                aria-pressed="{{ 'small' === data.model.text }}" 
                data-tippy-content="<?= _x('Use the Small Text setting in this field', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M10.6842105,7.5 L17,7.5 L17,8.62487194 L14.9699248,8.62487194 L14.9699248,19.498634 L13.8421053,19.498634 L13.8421053,8.62487194 L11.6616541,8.62487194 L11.6616541,19.498634 L10.5338346,19.498634 L10.5338346,14.8491633 L10.0075188,14.7741719 C8.27819549,14.474206 7,12.9743768 7,11.1745817 C7,9.37478656 8.27819549,7.87495731 10.0075188,7.57499146 C10.2322159,7.52742553 10.4577798,7.50242838 10.6842105,7.5 Z"/>
                </svg>
            </button>
        </div>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if (data.model.is_bold) { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.is_bold }}" 
                data-action="is_bold" 
                data-tippy-content="<?= _x('Make the text bold', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m14.7 11.3c1-.6 1.5-1.6 1.5-3 0-2.3-1.3-3.4-4-3.4h-5.2v14h5.8c1.4 0 2.5-.3 3.3-1s1.2-1.7 1.2-2.9c.1-1.9-.8-3.1-2.6-3.7zm-5.1-4h2.3c.6 0 1.1.1 1.4.4s.5.7.5 1.2-.2 1-.5 1.2c-.3.3-.8.4-1.4.4h-2.3zm4.6 9c-.4.3-1 .4-1.7.4h-2.9v-3.9h2.9c.7 0 1.3.2 1.7.5s.6.8.6 1.5-.2 1.2-.6 1.5z"/>
                </svg>
            </button>
        </div>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if (data.model.is_italic) { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.is_italic }}" 
                data-action="is_italic" 
                data-tippy-content="<?= _x('Make the text italic', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m17.063 7.154h-2.294l-3.015 9.692h2.283l-.668 2.154h-7.269l.668-2.154h2.304l3.016-9.692h-2.294l.668-2.154h7.269z"/>
                </svg>
            </button>
        </div>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if (data.model.is_uppercase) { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.is_uppercase }}" 
                data-action="is_uppercase" 
                data-tippy-content="<?= _x('Make the text uppercase', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M17.4921105,5 C18.7830375,5 19.6045365,5.25225225 20.3086785,5.88288288 C20.8954635,6.38738739 21.2475345,7.27027027 21.2475345,8.40540541 C21.2475345,9.03603604 21.2039367,9.56123661 20.9918755,10.2087651 C20.7778263,10.6534442 20.4260355,11.0540541 19.9566075,11.3063063 C20.7781065,11.5585586 21.3648915,11.9369369 21.8343195,12.5675676 C22.3435434,13.4504505 22.5384615,14.0310577 22.5384615,14.963964 C22.5384615,16.2252252 22.0690335,17.2342342 21.3648915,17.990991 C20.6607495,18.6216216 19.6045365,19 18.3136095,19 L18.3136095,19 L13.3846154,19 L13.3846154,5 Z M7.96114195,5 L12.8461538,19 L10.8921491,19 L9.54877082,15.25 L4.29738303,15.25 L2.95400476,19 L1,19 L5.8850119,5 L7.96114195,5 Z M18.0788955,12.5675676 L15.0276134,12.5675676 L15.0276134,17.3603604 L18.0788955,17.3603604 C18.7830375,17.3603604 19.3698225,17.1081081 19.8392505,16.7297297 C20.3086785,16.2252252 20.4918412,15.7288151 20.4918412,14.963964 C20.4918412,13.3225094 19.7218935,12.5675676 18.0788955,12.5675676 L18.0788955,12.5675676 Z M6.98413957,7.625 L4.90800952,13.5 L9.06026963,13.5 L6.98413957,7.625 Z M17.2573964,6.63963964 L15.1449704,6.63963964 L15.1449704,10.9279279 L17.2573964,10.9279279 C17.9615385,10.9279279 18.5743173,10.7113568 18.9003945,10.4234234 C19.2264716,10.13549 19.4871795,9.59305458 19.4871795,8.78378378 C19.4871795,7.97451298 19.2524655,7.3963964 18.9003945,7.14414414 C18.5483235,6.76576577 17.9615385,6.63963964 17.2573964,6.63963964 L17.2573964,6.63963964 Z"/>
                </svg>
            </button>
        </div>
        <div class="glsrt-toolbar-group">
            <button type="button" class="components-button has-icon <# if (data.model.is_hidden) { #>is-pressed<# } #>" 
                aria-pressed="{{ data.model.is_hidden }}" 
                data-action="is_hidden" 
                data-tippy-content="<?= _x('Hide field unless viewed in modal', 'admin-text', 'site-reviews-themes'); ?>">
                <svg aria-hidden="true" focusable="false" width="24" height="24" viewBox="0 0 24 24">
                    <path fill="currentColor" d="m19.2478445 3.69149532 1.0606602 1.06066017-.0606602-.06066017-15.55634918 15.55634918-1-1 3.17681562-3.176665c-1.17257763-.7091823-2.22577744-1.677549-3.07710215-2.8943017-.2967033-.4056246-.59340659-.8112492-.79120879-1.2168738.79120879-1.31828 1.78021978-2.53515387 2.96703297-3.44780924 2.91530883-2.20937199 6.60224203-2.54492316 9.73356373-1.27348752zm-.9593255 5.08172272c.7154179.58392579 1.3638405 1.27604026 1.9202722 2.07131826.2967033.3042184.4945055.709843.7912088 1.1154677-.2967033.4056246-.4945055.8112492-.7912088 1.2168738-.6923077 1.0140615-1.4835165 1.7239046-2.3736264 2.4337477-2.4328434 1.7352797-5.3464825 2.2526247-8.03008898 1.6450774l1.01629178-1.0154897c.3881319.0530635.7817247.0802553 1.1786324.0802553 3.0659341 0 5.9340659-1.6224984 7.6153846-4.2590584-.6451471-.9371079-1.407194-1.7243899-2.2702542-2.34555691zm-2.2406714 2.24106196c.0047162.0785323.0070975.1572066.0070975.2358809v.1014061c0 2.3323416-1.8791209 4.0562462-4.0549451 4.0562462-.1121416 0-.222841-.0046827-.3319678-.0138536zm-7.01488057-2.50208524c-1.87912088.60843691-3.46153846 1.82531074-4.64835165 3.54921534.85254746 1.3369223 2.01024915 2.4131085 3.34415943 3.1492313l1.25760385-1.2561928c-.59408377-.7363958-.94242262-1.6888275-.94242262-2.7042877 0-1.0140615.2967033-1.92671692.98901099-2.73796614zm4.84615387 0c-.4945055-.50703077-1.2857143-.50703077-1.7802198 0-.4945055.50703076-.4945055 1.31827998 0 1.82531074.0865743.0887671.1822427.1619936.2838208.2196793l1.7255942-1.72547766c-.0583658-.11477314-.1347642-.22268956-.2291952-.31951238z"/>
                </svg>
            </button>
        </div>
    <# } #>
    <# if (!_.isEmpty(data.model) && data.is_container && data.parent.direction === 'row') { #>
        <div class="glsrt-toolbar-group">
            <div class="glsrt-toolbar-group-input">
                <div class="glsrt-input">
                    <input data-action="minwidth" type="number" class="glsr-input-value small-text"
                        data-tippy-content="<?= _x('Adjust the minimum width, enter 0 to remove', 'admin-text', 'site-reviews-themes'); ?>"
                        data-tippy-allowHTML="true"
                        data-tippy-offset="[-50, 19]"
                        min="0"
                        value="{{ data.model.minwidth }}"
                    >
                    <span>px</span>
                </div>
            </div>
        </div>
    <# } #>
    <# if (!_.isEmpty(data.model) && data.is_container) { #>
        <div class="glsrt-toolbar-group">
            <div class="glsrt-toolbar-group-input">
                <div class="glsrt-input">
                    <input data-action="gap" type="number" class="glsr-input-value small-text"
                        data-tippy-content="<?= _x('Adjust the spacing between fields', 'admin-text', 'site-reviews-themes'); ?>"
                        data-tippy-allowHTML="true"
                        data-tippy-offset="[-50, 19]"
                        min="0"
                        value="{{ data.model.gap }}"
                    >
                    <span>px</span>
                </div>
            </div>
        </div>
    <# } #>
</script>
