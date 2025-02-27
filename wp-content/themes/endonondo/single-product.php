<?php
$pid = get_the_ID();
$product = wc_get_product($pid);
get_header('shop');
$terms_cat = get_the_terms($pid, 'product_cat');
$ratingInfo = glsr_get_ratings([
  'assigned_posts' => $pid
]);
$average = $ratingInfo->average;
$percent = ($average / 5) * 100;
$sub_percent = 100 - $percent;
$rating_total = 0;
if ($ratingInfo) {
  foreach ($ratingInfo->ratings as $rt) {
    $rating_total += $rt;
  }
}



?>
<style>
  .single-product .woocommerce-product-gallery__wrapper img {
    height: 530px;
    object-fit: cover;
  }

  #breadcrumbs .breadcrumb-separator {
    color: #aaa;
    font-size: 1.2em;
    margin: 0 5px;
    vertical-align: middle;
  }

  .wpcbn-btn {
    display: none !important;
  }

  .pdc-review-list .glsr-filters {
    width: 147px !important;
    padding: 7px 14px !important;
  }

  .product-cat-infor {
    align-items: center;
  }

  @media (max-width: 768px) {
    .woocommerce-notices-wrapper {
      margin-top: 20px;
    }

    .single-product .woocommerce-product-gallery__wrapper img {
      height: 343px;
    }

    .variations {
      display: grid;
    }

    .pdt-sdes ul li {
      font-size: 15px !important;
    }
  }

  .pdc-review-sumary .glsr-bar-background {
    width: 185px;
  }

  .swiper-slide .product-cat-infor {
    align-items: center;
  }

  @media (max-width: 1097px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 155px;
    }
  }

  @media (max-width: 1097px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 155px;
    }
  }

  @media (max-width: 983px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 115px;
    }
  }

  @media (max-width: 835px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 70px;
    }
  }

  @media (max-width: 765px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 400px;
    }
  }

  @media (max-width: 768px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 400px;
    }
  }

  @media (max-width: 539px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 270px;
    }
  }

  @media (max-width: 414px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 290px;
    }
  }

  @media (max-width: 380px) {
    .pdc-review-sumary .glsr-bar-background {
      width: 270px;
    }
  }

  .product-related-wrap {
    margin-top: -40px;
    background-color: #F8F8F8;
  }

  .feature-item {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 20px;
    margin-bottom: 20px;
  }

  .feature-icon {
    margin-bottom: 15px;
  }

  .feature-img img {
    max-width: 369px;
    height: auto;
    max-height: 274px;
  }

  .feature-infor {
    text-align: left;
    flex: 1;
    min-width: 200px;
  }

  .feature-title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
  }

  .feature-detail {
    font-size: 16px;
    color: #000;
    line-height: 1.5;
  }

  @media (max-width: 768px) {
    .feature-item {
      flex-direction: column;
    }

    .feature-item.th2 {
      flex-direction: column;
    }

    .feature-item.th2 .feature-infor {
      order: 2;
    }

    .feature-item.th2 .feature-img {
      order: 1;
    }

    .feature-img img {
      max-width: 100%;
      height: auto;
    }

    .feature-infor {
      margin-top: 10px;
    }

    .feature-title {
      font-size: 16px;
    }

    .feature-detail {
      font-size: 15px;
    }
  }

  @media (max-width: 480px) {
    .feature-img img {
      width: 100%;
    }

    .feature-title {
      font-size: 18px;
    }

    .feature-detail {
      font-size: 15px;
    }
  }

  .product_table {
    margin-top: 30px;
    width: 100%;
  }

  .table-wrapper {
    -webkit-overflow-scrolling: touch;
    width: 100%;
    scrollbar-width: none;
  }

  .table-wrapper::-webkit-scrollbar {
    display: none;
  }

  .information_table {
    width: max-content;
    margin-top: -30px;
    border-collapse: separate;
    border: 1px solid #ddd;
    table-layout: auto;
    white-space: nowrap;
  }

  .information_table thead th {
    color: #4CAF50;
    text-align: center;
    padding: 12px;
  }

  .information_table tbody th {
    background-color: #f9f9f9;
    text-align: left;
    padding: 10px;
  }

  .information_table td {
    padding: 10px;
    vertical-align: top;
    text-align: left;
  }

  .information_table tbody tr:nth-child(odd) td {
    background-color: #f9f9f9;
  }

  .information_table tbody tr:nth-child(even) td {
    background-color: #ffffff;
  }

  .information_table tbody tr:nth-child(odd) th {
    background-color: #f9f9f9;
  }

  .information_table tbody tr:nth-child(even) th {
    background-color: #ffffff;
  }

  .information_table td,
  .information_table th {
    border: none;
    padding: 18px;
  }

  .information_table ul {
    margin-bottom: -50px;
    margin-top: -10px;
  }

  .information_table ul li {
    list-style: disc;
  }

  .information_table ul li::marker {
    color: #000 !important;
  }

  .information_table ul li::before {
    display: none !important;
  }

  .information_table td:first-child,
  .information_table th:first-child {
    width: 150px;
  }

  .information_table td,
  .information_table th {
    max-width: 300px;
    word-wrap: break-word;
  }

  @media (max-width: 768px) {
    .table-wrapper {
      overflow-x: auto;
    }

    .information_table {
      font-size: 15px;
    }

    .information_table ul li {
      font-size: 15px;
    }

    .name-table {
      font-size: 15px;
    }
  }

  .choices-container {
    display: flex;
    justify-content: space-between;
    gap: 24px;
    flex-wrap: wrap;
    margin-top: 20px;
  }

  .choice-card {
    position: relative;
    background-color: #f9f9f9;
    padding: 20px 20px 60px;
    text-align: center;
    flex: 1 1 calc(50% - 20px);
    max-width: 400px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  }

  .choice-image img {
    max-width: 100%;
    height: auto;
    margin-bottom: 15px;
  }

  .choice-card h3 {
    font-size: 20px !important;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
  }

  .choice-details {
    margin-top: 20px;
    text-align: left;
  }

  .choice-details h4 {
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 10px;
  }

  .choice-card hr {
    border: 0;
    border-top: 1px solid rgb(135, 203, 137);
    width: 200px;
    margin: 20px auto;
  }

  .choice-card .cons {
    padding-top: 0 !important;
    border-top: none !important;
    ;
  }

  .see-details {
    position: absolute;
    bottom: -24px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #000;
    color: #fff;
    border: none;
    padding: 15px 40px;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .see-details:hover {
    background-color: #333;
  }

  @media (max-width: 768px) {
    .choice-card {
      flex: 1 1 100%;
    }

    .choice-card h3 {
      font-size: 17px !important;
    }

    .choice-details h4 {
      font-size: 17px !important;
    }

    .product-detail-main .extra-content ul li {
      font-size: 15px !important;
    }
  }

  .product-info-buy {
    margin-top: 40px;
  }

  @media (max-width: 768px) {
    .pdc-review-sumary {
      flex-direction: column;
      gap: 25px;
    }

    .rating-box span {
      display: flex;
      gap: 4px;
    }

    .sumary-chart-info {
      width: 100% !important;
    }

    .c-review-sumary .pdc-sumary-center {
      width: 100% !important;
    }

    .pdc-review-sumary .pdc-sumary-center {
      padding: 0 0 !important;
    }

    .pdc-review-sumary .pdc-sumary-item {
      width: 100% !important;
    }

    .pdc-review-form {
      flex-direction: column;
    }

    .pdc-review-form .pdc-form-information {
      width: 100%;
    }

    .pdc-review-form .pdc-form-box {
      width: 100%;
    }

    .pdc-review-form .wp-block-button {
      display: flex;
      justify-content: center;
    }
  }

  @media (max-width: 485px) {
    .sumary-chart-info h3 {
      width: 200px;
      font-size: 17px !important;
      margin: 2px 0 !important;
    }

    .sumary-chart-info p {
      font-size: 12px !important;
    }
  }

  .price_vari {
    display: none;
  }

  .heading {

    text-align: left;
    margin-bottom: 30px;
    padding: 20px;
    background-color: #f9f9f9;
  }

  .heading h5 {
    font-size: 1rem;
    color: #8dc63f;
    margin-bottom: 10px;
    font-weight: normal;
    text-transform: uppercase;
  }

  .heading h2 {
    font-size: 2.5rem;
    font-weight: bold;
    color: #000;
    margin-bottom: 10px;
  }

  .heading p {
    font-size: 1rem;
    color: #666;
    line-height: 1.5;
  }

  /* Wrapper Styles */
  .product-related-wrap ul {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    list-style: none;
    padding: 0;
    margin: 0;
  }

  /* Product Card Styles */
  .product {
    flex: 1 1 calc(33.333% - 20px);
    max-width: calc(33.333% - 20px);
    box-sizing: border-box;
    border: none;
    border-radius: 0;
    padding: 15px;
    text-align: center;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08), 0 0px 4px rgba(0, 0, 0, 0.04);
    margin: 0;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
  }

  .product:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
  }

  /* Product Image Styles */
  .product img {
    width: 100%;
    max-width: 100%;
    height: 300px;
    object-fit: cover;
    margin: 0;
    border-radius: 0;
  }

  /* Product Info Styles */
  .product .ht-prod-info {
    text-align: left;
  }

  .woocommerce-loop-product__title {
    font-family: 'Inter', sans-serif;
    font-size: 20px;
    line-height: 1.3;
    font-weight: 500;
    margin: 5px 0;
    color: #000;
  }

  /* Rating Styles */
  .custom-product-rating {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 5px;
    margin: 0 0 8px;
    font-size: 25px;
  }

  .custom-product-rating .star {
    color: #000;
  }

  .custom-product-rating .rating-count {
    font-size: 12px;
    color: #9F9F9F;
  }

  /* Price Styles */
  .price {
    font-family: 'Inter', sans-serif;
    font-size: 20px;
    color: #000;
    font-weight: 500;
  }

  .price del {
    font-size: 14px;
    color: #999;
    margin-right: 5px;
  }

  /* Add to Cart Button */
  .add_to_cart_button {
    background: none !important;
    padding: 20px !important;
    transition: background-color 0.3s ease;
  }

  .add_to_cart_button {
    position: absolute !important;
    top: -10px;
    right: -7px;
  }

  .product .add_to_cart_button:hover {
    background-color: transparent !important;
  }

  /* Product Status */
  .pstatus {
    background: #000;
  }

  /* Shop Banner */
  .shop-banner {
    text-align: left;
    padding: 75px 0;
    font-family: 'Inter', sans-serif;
  }

  .shop-banner h1 {
    font-size: 48px;
    line-height: 54px;
    color: #fff;
  }

  .shop-banner form label {
    font-size: 0;
    width: 100%;
    display: block;
  }

  @media (max-width: 485px) {
    .product-related-wrap ul {
      flex-direction: column;
    }

    .product-related-wrap ul li {
      max-width: 100%;
    }
  }

  #yith-wapo-container {
    flex: 0 0 100%;
    max-width: 100%;
  }

  .yith-wapo-addon {
    background: none !important;
  }

  .ht-actions-woo.external .single_add_to_cart_button {
    width: 100%;
    text-align: center;
    background: #151515 !important;
    color: #fff !important;
    padding: 16px !important;
    height: 62px;
    line-height: 30px;
    transition: all .3s;
    border-radius: 0 !important;
  }

  .ht-actions-woo.external .single_add_to_cart_button:hover {
    background: #FF5757 !important;
    border-color: #FF5757 !important;
    color: #fff !important;
  }

  .ht-actions-woo.external .ht_buy_now_button {
    display: none !important;
  }

  #yith-wapo-container {
    flex: 0 0 100%;
    max-width: 100%;
  }

  .yith-wapo-addon {
    background: none !important;
  }

  .ht-actions-woo.external .single_add_to_cart_button {
    width: 100%;
    text-align: center;
    background: #151515 !important;
    color: #fff !important;
    padding: 16px !important;
    height: 62px;
    line-height: 30px;
    transition: all .3s;
    border-radius: 0 !important;
  }

  .ht-actions-woo.external .single_add_to_cart_button:hover {
    background: #FF5757 !important;
    border-color: #FF5757 !important;
    color: #fff !important;
  }

  .ht-actions-woo.external .ht_buy_now_button {
    display: none !important;
  }

  .pdc-review-sumary .glsr-summary-rating,
  .glsr-summary-stars,
  .pdc-review-sumary .glsr-summary-text {
    display: none;
  }

  .pdc-review-sumary .glsr-summary-percentages {
    font-size: 14px;
    line-height: 20px;
    color: #5d5d5d;
    gap: 12px 0;
    display: flex;
    flex-wrap: wrap;
  }

  .pdc-review-sumary .glsr-bar-background {
    background: #f4f6f8;
  }

  .pdc-review-sumary .glsr-summary .glsr-bar-background-percent {
    background: #151515;
  }

  .pdc-review-sumary .pdc-sumary-item {
    width: 35%;
  }

  .pdc-review-sumary .pdc-sumary-center {
    padding: 0 18px;
    width: 26%;
  }

  .glsr-summary .glsr-bar {
    width: 100%;
  }

  .glsr-summary .glsr-bar-label {
    padding-right: 20px;
    position: relative;
  }

  .glsr-summary .glsr-bar-label:after {
    width: 13px;
    height: 13px;
    background: url(<?php echo get_template_directory_uri() ?>/assets/images/star-red.svg) no-repeat center center / 100% auto;
    position: absolute;
    top: 3px;
    right: 4px;
    content: "";
  }

  #pdc-review .pdc-review-sumary {
    padding-bottom: 20px;
    border-bottom: 1px solid #bbb;
    align-items: flex-start;
  }

  .pdc-review-list {
    position: relative;
    padding: 20px 0;
  }

  .pdc-review-list .pdc-list-review {
    font-size: 16px;
    color: #151515;
    line-height: 30px;
    font-weight: 700;
    letter-spacing: -0.05em;
    display: inline-block;
    padding: 8px 29px;
    border: 1px solid #bbb;
    position: absolute;
    top: 20px;
    right: 0;
  }

  .pdc-review-list .glsr-reviews-wrap {
    gap: 0;
  }

  .pdc-review-list .glsr-filters {
    display: inline-block;
    border: 1px solid #bbb;
    padding: 7px 16px;
    width: 131px;
  }

  .pdc-review-list .glsr-sort-by label {
    font-size: 10px;
    line-height: 16px;
    font-weight: 400;
    color: #151515;
    font-family: "Arimo";
    text-transform: none;
    letter-spacing: 0;
  }

  .glsr .glsr-filters form.glsr-filters-form select.glsr-select {
    border: none;
    padding: 0 !important;
    font-size: 12px;
    font-weight: 700;
    line-height: 16px;
    color: #151515;
    font-family: "Arimo";
    -moz-appearance: none;
    -webkit-appearance: none;
    appearance: none;
    position: relative;
    background: url(<?php echo get_template_directory_uri() ?>/assets/img/select-icon.svg) no-repeat top right/15px auto;
    outline: none !important;
  }

  .pdc-review-list .glsr-review {
    padding: 20px 0 !important;
    box-shadow: none !important;
    border-bottom: 1px solid #bbb !important;
    position: relative;
    border-radius: 0 !important;
  }

  .pdc-review-list .glsr-review .gl-items-center:first-child {
    position: absolute;
    top: 0;
    left: 0;
    padding-left: 72px;
    min-height: 60px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0 !important;
    align-items: flex-start;
  }

  .pdc-review-list .glsr-review .gl-items-center:first-child .gl-text-normal[data-tag="avatar"] {
    position: absolute;
    left: 0;
    top: 0;
  }

  .pdc-review-list .glsr-review .gl-items-center:first-child .glsr-review-verified {
    color: #000;
    font-size: 10px;
    line-height: 1;
    letter-spacing: 0;
  }

  .pdc-review-list .glsr-review .gl-items-center:first-child svg {
    height: 14px;
    width: 14px;
  }

  .pdc-review-list .gl-items-stretch {
    padding: 0 383px 0 193px;
    position: relative;
    min-height: 190px;
    gap: 18px !important;
  }

  .product-detail-content {
    max-width: 1100px !important;
  }

  .product-detail-content .pdc-box,
  .product-detail-content .pdc-nav {
    max-width: 778px;
    margin: 0 auto;
  }

  .product-detail-content .pdc-nav {
    margin-bottom: 24px;
  }

  .pdc-review-list .glsr-review .gl-items-center .glsr-tag-value {
    font-size: 16px;
    line-height: 24px;
  }

  .glsr .glsr-review-actions:after,
  .gl-text-normal[data-tag="assigned_links"] {
    display: none !important;
  }

  .glsr .glsr-review-actions {
    padding: 0;
  }

  .gl-text-normal[data-tag="images"] {
    position: absolute;
    right: 0;
    top: 0;
  }

  .gl-text-normal[data-tag="images"] .glsr-review-images {
    gap: 50px;
    display: flex;
    width: 220px;
    margin: 0;
    flex-wrap: wrap;
  }

  .glsr .glsr-review-images a.glsr-image {
    width: calc(50% - 25px);
  }

  .pdc-review-form {
    display: flex;
    gap: 24px;
  }

  .pdc-form-information,
  .pdc-form-box {
    width: calc(50% - 12px);
  }

  #content .box-main-content .content-main #pdc-review .pdc-form-information h2 {
    margin-bottom: 16px;
  }

  .pdc-form-information p {
    font-size: 15px;
    line-height: 23px;
    margin-bottom: 16px;
  }

  .pdc-form-information h3 {
    font-size: 18px;
    line-height: 20px;
    margin-bottom: 24px;
  }

  .pdc-form-information img {
    max-width: 300px;
  }

  .pdc-form-box .glsr form.glsr-form-responsive {
    display: flex;
    flex-wrap: wrap;
    gap: 16px 24px;
  }

  .pdc-form-box .glsr-field {
    width: 100%;
  }

  .glsr-default form.glsr-form label.glsr-label {
    font-size: 16px;
    line-height: 30px;
    font-weight: 700;
    margin-bottom: 5px;
  }

  form.glsr-form .glsr-star-rating--stars[class*=" s"]>span {
    font-size: 10px;
  }

  .pdc-form-box .glsr-input {
    border-color: #bbb;
    border-radius: 0;
    font-size: 14px;
    line-height: 18px;
    padding: 10px !important;
  }

  .pdc-form-box .glsr-field[data-field="name"],
  .pdc-form-box .glsr-field[data-field="email"] {
    width: calc(50% - 12px);
  }

  .pdc-form-box .glsr-field[data-field="images"] {
    width: 200px;
    padding-bottom: 32px;
    position: relative;
  }

  .pdc-form-box .glsr-field .glsr-description {
    position: absolute;
    bottom: 0;
    left: 0;
    font-size: 14px;
    line-height: 24px;
    font-style: italic;
    color: #151515;
  }

  .glsr form.glsr-form .glsr-dropzone:after {
    opacity: 0;
  }

  .pdc-form-box div[data-field="submit-button"] {
    width: 100%;
  }

  .pdc-form-box div[data-field="submit-button"] .glsr-button {
    background: #151515;
    border: none;
    border-radius: 0;
    font-size: 16px;
    line-height: 1;
    font-weight: 700;
    padding: 15.5px 43.5px;
  }

  .glsr form.glsr-form .glsr-dropzone {
    font-size: 0;
    border: 1px solid #bbb;
    position: relative;
    background: url(<?php echo get_template_directory_uri() ?>/assets/images/upload.svg) no-repeat left 47px center/15px auto;
    border-radius: 0;
    text-align: center;
  }

  .glsr form.glsr-form .glsr-dropzone:before {
    content: "Upload File";
    font-size: 16px;
    line-height: 30px;
    color: #151515;
    font-weight: 700;
    position: relative;
    top: 8px;
    left: 15px;
  }

  .pdc-sumary-right {
    display: flex;
    flex-wrap: wrap;
    gap: 14px 12px;
  }

  .pdc-sumary-right .item {
    padding: 7px 15px;
    border: 1px solid #bbb;
    font-size: 16px;
    line-height: 1;
    color: #151515;
    height: 40px;
    background: #fff;
    cursor: pointer;
  }

  .pdc-sumary-right .item.active {
    background: #151515;
    border-color: #151515;
    color: #fff;
  }

  .pdc-review-sumary .pdc-sumary-left {
    width: 39%;
    display: flex;
    align-items: center;
  }

  .sumary-chart {
    width: 148px;
    position: relative;
  }

  .sumary-chart-number {
    font-size: 42px;
    line-height: 48px;
    font-weight: 700;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  .sumary-chart-info {
    width: calc(100% - 148px);
    padding-left: 18px;
  }

  #content .box-main-content .content-main .sumary-chart-info h3 {
    margin: 8px 0 !important;
    font-size: 18px;
    line-height: 20px;
  }

  .sumary-chart-info p {
    font-size: 12px;
    line-height: 20px;
    margin: 0 !important;
  }

  .rating-box {
    display: flex;
  }

  .rating-box .rating-item {
    height: 20px;
    width: 20px;
    background: url(<?php echo get_template_directory_uri() ?>/assets/images/empty-star.svg) no-repeat center center/100% auto;
  }

  .rating-box .rating-item.active {
    background-image: url(<?php echo get_template_directory_uri() ?>/assets/images/filled-star.svg);
  }

  .rating-box .rating-symbol {
    height: 20px;
    width: 20px;
  }

  .rating-box .glyphicon-star-empty {
    background: url(<?php echo get_template_directory_uri() ?>/assets/images/empty-star.svg) no-repeat center center/100% auto;
    height: 20px;
  }

  .rating-box .rating-symbol-foreground {
    background: url(<?php echo get_template_directory_uri() ?>/assets/images/filled-star.svg) no-repeat left center/auto 100%;
    height: 20px;
    top: 0;
  }

  .woocommerce-variation-price p.price>span:nth-child(2):before {
    content: '-';
    font-weight: bold;
    margin-right: 8px;
  }

  .ht-rating-icon .rating-box>span:first-child {
    display: none;
  }
</style>

<main id="main" class="product-detail-main hea">
  <div class="product-detail-wrap">
    <div class="link-page">
      <div class="container">
        <?php
        if (function_exists('yoast_breadcrumb')) {
          if (is_product()) {
            $product = wc_get_product(get_the_ID());
            $categories = get_the_terms($product->get_id(), 'product_cat');

            if (!empty($categories)) {
              $main_category = $categories[0];
              foreach ($categories as $category) {
                if ($category->parent == 0) {
                  $main_category = $category;
                  break;
                }
              }
            }

            echo '<nav aria-label="breadcrumb" id="breadcrumbs">';
            echo '<a href="' . home_url() . '">' . 'Home' . '</a>';
            echo '<span class="breadcrumb-separator"> | </span>';
            echo '<a href="' . get_permalink(wc_get_page_id('shop')) . '">' . 'Shop' . '</a>';
            if (!empty($main_category)) {
              echo '<span class="breadcrumb-separator"> | </span>';
              echo '<a href="' . get_term_link($main_category) . '">' . $main_category->name . '</a>';
            }
            echo '<span class="breadcrumb-separator"> | </span>';
            echo '<span>' . $product->get_name() . '</span>';
            echo '</nav>';
          } else {
            yoast_breadcrumb('<p id="breadcrumbs">', '</p>');
          }
        }
        ?>

      </div>
    </div>
    <div class="container">
      <?php
      do_action('woocommerce_before_single_product');
      ?>
      <div class="product-detail-top list-flex">
        <div class="pdt-left">
          <div class="ht-gallery-product">
            <?php woocommerce_show_product_images() ?>
          </div>
        </div>
        <div class="pdt-right">
          <div class="pdt-cat list-flex">
            <?php
            $terms = get_the_terms($pid, 'product_brand');
            if (! empty($terms) && ! is_wp_error($terms)) {
            ?>
              <div class="cat-item">Brand:
                <?php
                foreach ($terms as $t => $term) {
                  $term_link = get_term_link($term->term_id, 'product_brand');
                  echo '<a href="' . $term_link . '">' . esc_html($term->name) . '</a>';
                  if ($t > 0) echo ' ,';
                }
                ?>
              </div>
            <?php }
            $terms = get_the_terms($pid, 'product_cat');
            if (! empty($terms) && ! is_wp_error($terms)) {
            ?>
              <div class="cat-item">Category:
                <?php
                $terms_count = count($terms);
                foreach ($terms as $t => $term) {
                  $term_link = get_term_link($term->term_id, 'product_cat');
                  echo '<a href="' . $term_link . '">' . esc_html($term->name) . '</a>';
                  if ($t < $terms_count - 1) {
                    echo ', ';
                  }
                }
                ?>
              </div>
            <?php } ?>
          </div>
          <h1 class="pdt-title"><?php the_title(); ?></h1>
          <div class="ht-rating list-flex">
            <div class="ht-rating-icon list-flex">
              <div class="rating-box">
                <input type="hidden" class="rating" value="<?php echo $average; ?>" />
              </div>
            </div>
            <div class="ht-rating-text">
              <b><?php echo $average; ?> Star Rating</b> (<?php echo $rating_total; ?> User feedback)
            </div>
          </div>
          <div class="pdt-price list-flex flex-middle ">
            <div class="woocommerce-variation-price">
              <span class="price">
                <?php woocommerce_template_single_price(); ?>
              </span>
            </div>

            <?php
            if ($product->is_type('variable')) {
              $regular_price = $product->get_variation_regular_price('min');
              $sale_price = $product->get_variation_sale_price('min');
            } else {
              $regular_price = $product->get_regular_price();
              $sale_price = $product->get_sale_price();
            }
            ?>
            <?php
            if ($sale_price) {
              $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
              echo '<div class="pstatus pstatus-save">SAVE ' . $discount_percentage . '%</div>';
            }
            if ($product->is_featured()) {
            ?>
              <div class="pstatus pstatus-bsell">BEST SELLER</div>
            <?php
            }
            ?>
          </div>
          <div class="pdt-des"><?php the_excerpt(); ?></div>
          <div class="ht-variations">
            <?php woocommerce_upsell_display(); ?>
          </div>
          <?php
          if ($product->is_sold_individually()) {
          ?>
            <style>
              .quantity .minus,
              .quantity .plus {
                pointer-events: none;
                opacity: 0.5;
                visibility: hidden;
              }
            </style>
          <?php } ?>
          <div class="ht-actions-woo <?php echo $product->get_type() ?>">
            <?php woocommerce_template_single_add_to_cart(); ?>
          </div>
          <div class="pdt-sdes"><?php the_field('description_short'); ?></div>
          <?php 
            $on_off_adc = get_field('on_off_adc', $pid);
            if($on_off_adc == true){
              $advc = get_field('adcontent', 'option');
              if ($advc) { ?>
              <div class="text-more"><?php echo $advc; ?></div>
          <?php } } ?>

          <?php
          $WC_Structured_Data = new WC_Structured_Data();
          $WC_Structured_Data->generate_product_data();
          ?>

        </div>
      </div>
    </div>
  </div>
  <div class="product-detail-content-wrap">
    <div class="container">
      <div id="content" class="product-detail-content">
        <div class="pdc-nav">
          <a href="#pdc-des" class="nav-item active">Description</a>
          <a href="#pdc-spec" class="nav-item">How to use</a>
          <a href="#pdc-review" class="nav-item">Review</a>
        </div>
        <div class="box-main-content">
          <div class="content-main">
            <article class="single-custom sma-list extra-content">
              <div id="pdc-des" class="pdc-item">
                <div class="pdc-box">
                  <?php the_content(); ?>

                  <div class="pros-cons">
                    <?php if (get_field('product_pros') || get_field('product_cons')) { ?>
                      <h3>Pros & Cons</h3>
                      <div class="pros">
                        <?php the_field('product_pros'); ?>
                      </div>
                      <div class="cons">
                        <?php the_field('product_cons'); ?>
                      </div>
                    <?php } ?>
                    <?php if (get_field('product_size_image')) { ?>
                      <div class="size-image">
                        <img src="<?php the_field('product_size_image'); ?>" alt="Size image">
                      </div>
                    <?php } ?>

                    <div class="features">
                      <?php if (get_field('product_features')) { ?>
                        <?php if (get_field('product_features_title')) { ?>
                          <h3><?php the_field('product_features_title'); ?></h3>
                        <?php } ?>
                        <?php
                        $product_features = get_field('product_features');
                        if (!empty($product_features) && is_array($product_features)) {
                          foreach ($product_features as $index => $feature) {
                            if (isset($feature['product_feature_image'], $feature['product_feature_title'], $feature['product_feature_detail'])) {
                              echo '<div class="feature-item ' . ($index > 0 ? 'th2' : '') . '">';
                              echo '<div class="feature-img"><img src="' . esc_url($feature['product_feature_image']) . '" alt="Feature img"></div>';
                              echo '<div class="feature-infor">';
                              echo '<div class="feature-title">' . esc_html($feature['product_feature_title']) . '</div>';
                              echo '<div class="feature-detail">' . esc_html($feature['product_feature_detail']) . '</div>';
                              echo '</div>';
                              echo '</div>';
                            }
                          }
                        }
                        ?>
                      <?php } ?>
                    </div>
                    <div class="product_table">
                      <?php if (get_field('product_information_table')) { ?>
                        <div class="name-table"><?php the_field('product_information_table_lable'); ?></div>
                        <div class="table-wrapper">
                          <?php the_field('product_information_table'); ?>
                        </div>
                      <?php } ?>
                    </div>
                    <?php if (get_field('alternative_choices_product_status')) { ?>
                      <div class="alternative-choices">
                        <h2>Alternative Choices</h2>
                        <div class="choices-container">
                          <?php
                          $products = get_field('item_alternative_choices');
                          if (!empty($products)) {
                            foreach ($products as $item) {
                          ?>
                              <div class="choice-card">
                                <a href="<?php echo $item['url'] ? ($item['url']) : '#'; ?>">
                                  <div class="choice-image">
                                    <img src="<?php echo $item['image'] ?? ""; ?>" alt="<?php echo $item['title'] ?? ""; ?>" />
                                  </div>
                                </a>

                                <a href="<?php echo $item['url'] ? ($item['url']) : '#'; ?>">
                                  <h3><?php echo $item['title'] ?? ""; ?></h3>
                                </a>
                                <div class="choice-details">
                                  <hr>
                                  <div class="pros">
                                    <h4>Pros</h4>
                                    <ul>
                                      <?php echo $item['pros'] ?? ""; ?>
                                    </ul>
                                  </div>
                                  <hr>
                                  <div class="cons">
                                    <h4>Cons</h4>
                                    <ul>
                                      <?php echo $item['cons'] ?? ''; ?>
                                    </ul>
                                  </div>
                                </div>
                                <button class="see-details" onclick="window.location.href='<?php echo $item['url'] ? ($item['url']) : '#'; ?>'">
                                  See Details
                                </button>
                              </div>
                          <?php
                            }
                          }
                          ?>
                        </div>
                      </div>
                    <?php } ?>
                    <div class="product-info-buy">
                      <?php the_field('product_information_buy'); ?>
                    </div>
                  </div>
                </div>
                <div id="pdc-spec" class="pdc-item">
                  <div class="pdc-box">
                    <?php the_field('product_to_use'); ?>
                    <?php the_field('product_reviewh'); ?>
                  </div>
                </div>
                <div id="pdc-review" class="pdc-item">
                  <h2>Customer Reviews</h2>
                  <div class="pdc-review-sumary list-flex">
                    <div class="pdc-sumary-left pdc-sumary-item">
                      <div class="sumary-chart">
                        <div class="sumary-chart-number"><?php echo $average; ?></div>
                        <canvas id="myChart" width="265" height="265"></canvas>
                      </div>
                      <div class="sumary-chart-info">
                        <div class="rating-box">
                          <input type="hidden" class="rating" value="<?php echo $average; ?>" />
                        </div>
                        <h3><?php echo $percent; ?>% of buyers are satisfied</h3>
                        <p><?php echo $rating_total; ?> Reviews</p>
                      </div>
                    </div>
                    <div class="pdc-sumary-center pdc-sumary-item">
                      <?php echo do_shortcode('[site_reviews_summary theme="24785" assigned_posts="' . $pid . '" labels="5,4,3,2,1"]'); ?>
                    </div>
                    <div class="pdc-sumary-right pdc-sumary-item">
                      <button class="item active">All (<?php echo $rating_total; ?>) </button>
                      <?php
                      if ($ratingInfo) {
                        foreach ($ratingInfo->ratings as $r => $rt) {
                          if ($r > 0) {
                            echo '<button class="item">' . $r . ' Stars (' . $rt . ')</button>';
                          }
                        }
                      }
                      ?>
                    </div>
                  </div>
                  <div class="pdc-review-list">
                    <a class="pdc-list-review" href="javascript:void(0);">Write a Review</a>
                    <?php echo do_shortcode('[site_reviews theme="35812" assigned_posts="' . $pid . '" filters="sort_by"]'); ?>
                  </div>
                  <div class="pdc-review-form" style="display: none;">
                    <div class="pdc-form-information">
                      <h2>Review</h2>
                      <p>We’d love to hear your feedback! Please leave a review in the comments section below to share your thoughts and experiences.</p>
                      <h3 class="product-title"><?php the_title(); ?></h3>
                      <?php the_post_thumbnail(); ?>
                    </div>
                    <div class="pdc-form-box">
                      <?php echo do_shortcode('[site_reviews_form form="35811" assigned_posts="' . $pid . '"]'); ?>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                document.querySelector('.pdc-list-review').addEventListener('click', function() {
                  document.querySelector('.pdc-review-form').style.display = 'flex';
                  window.scrollTo({
                    top: document.querySelector('.pdc-review-form').offsetTop,
                    behavior: 'smooth'
                  });
                });
              </script>
            </article>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php
  $best_seller_top = get_field('best_sale_lable', 'option');
  ?>
  <div class="product-related-wrap">
    <div class="container">
      <div class="heading">
        <?php echo $best_seller_top; ?>
      </div>
      <?php
      $products_query = new WP_Query(array(
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => 3,
        'meta_key'       => 'total_sales',
        'orderby'        => 'meta_value_num',
        'order'          => 'DESC',
      ));

      echo "<ul>";
      if ($products_query->have_posts()) {
        while ($products_query->have_posts()) {
          $products_query->the_post();
          wc_get_template_part('content', 'product-related');
        }
        wp_reset_postdata();
      } else {
        echo '<p>No products found.</p>';
      }
      echo "</ul>";
      ?>
    </div>
  </div>
</main>
<div class="ht-custom-side-woo" style="display: none">
  <div class="ht-custom-side-woo-wrap">
    <div class="side-header">
      <a href="">My account</a>
      <button id="close-side" class="close-side">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
          <path d="M7.55273 6L11.9121 10.3594C12.0293 10.4766 12.0293 10.6055 11.9121 10.7461L11.1035 11.5547C10.9629 11.6719 10.834 11.6719 10.7168 11.5547L9.97852 10.7812L6.35742 7.19531L1.99805 11.5547C1.88086 11.6719 1.75195 11.6719 1.61133 11.5547L0.802734 10.7461C0.685547 10.6055 0.685547 10.4766 0.802734 10.3594L5.16211 6L0.802734 1.64062C0.685547 1.52344 0.685547 1.39453 0.802734 1.25391L1.61133 0.445312C1.75195 0.328125 1.88086 0.328125 1.99805 0.445312L6.35742 4.80469L10.7168 0.445312C10.834 0.328125 10.9629 0.328125 11.1035 0.445312L11.9121 1.25391C12.0293 1.39453 12.0293 1.52344 11.9121 1.64062L11.1387 2.37891L7.55273 6Z" fill="#151515" />
        </svg>
      </button>
    </div>
    <div class="side-list">
      <ul>
        <li>
          <a href="">Profile</a>
        </li>
        <li>
          <a href="">Orders</a>
        </li>
        <li>
          <a href="">Settings</a>
        </li>
      </ul>
    </div>
    <div class="side-action">
      <a href="">
        Log out
      </a>
    </div>
  </div>
</div>
<script>
  jQuery(function($) {
    $('form.variations_form').on('show_variation', function(event, variation) {
      console.log(1);
      if (variation.price_html) {
        $('.woocommerce-variation-price .price').html(variation.price_html);
      }
    });
  });

  jQuery('.product_attributes .pro_attr_select > a').on('click', function(e) {
    jQuery('.product_attributes .pro_attr_select').removeClass('selected');
    jQuery(this).closest('li.pro_attr_select').addClass('selected');
    jQuery('#potency').val($(this).data('name')).change();
    jQuery('.pdt-price > p.price').html($(this).find('.price').html());
    e.preventDefault();
  });

  jQuery('.pro_attr_select').each(function() {
    if (jQuery(this).find('a').data('name') == jQuery('#potency').val()) {
      jQuery(this).addClass('selected');
    }
  });

  jQuery('.pdc-nav a').click(function(e) {
    e.preventDefault();
    jQuery('.pdc-nav a').removeClass('active');
    jQuery(this).addClass('active');
    var height = jQuery('.pdc-nav').height();
    var full_url = this.href;
    var parts = full_url.split("#");
    var trgt = parts[1];
    var target_offset = $("#" + trgt).offset();
    var target_top = target_offset.top - (height + 120);
    jQuery('html, body').animate({
      scrollTop: target_top
    }, 500, );
  });

  jQuery(function($) {
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'doughnut',
      data: {
        labels: ['yes', 'no'],
        datasets: [{
          label: '# of Votes',
          data: [<?php echo $percent; ?>, <?php echo $sub_percent; ?>],
          backgroundColor: ['#151515', '#fff'],
          weight: 10,
          borderWidth: 0,
        }]
      },
      options: {
        cutoutPercentage: 85,
        legend: {
          display: false
        },
        tooltips: {
          enabled: false
        }
      }
    });
  })
</script>


<script>
  $('.variable-item').on('click', function(e) {
    $('.single-product .pdt-price p.price').html($(this).find('.price_vari').html());

    e.preventDefault();

  });
</script>
<script>
  jQuery(document).ready(function($) {
    $('.ht-actions-woo .quantity input.qty').each(function() {
      var maxVal = $(this).attr('max');

      if (maxVal == 1) {
        $(this).attr({
          'type': 'number',
          'readonly': true,
          'min': 1,
          'max': 1,
          'value': 1
        }).show();
      }
    });
  });
</script>

<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/woocommerce/css/product.css' ?>?ver=0.0.1">
<link rel="stylesheet" href="<?php echo get_template_directory_uri() . '/woocommerce/css/product-single.css' ?>?ver=0.0.4.9">

<?php get_footer('shop'); ?>