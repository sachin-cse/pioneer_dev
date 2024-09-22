<?php defined('BASE') OR exit('No direct script access allowed.');

if($data['settings']) {
    
	$IdToEdit                   = $data['settings']['id'];
    
    $isCategoryShowcase         = $data['settings']['isCategoryShowcase'];
    $categoryShowcaseTitle      = $data['settings']['categoryShowcaseTitle'];
    $categoryShowcaseNo         = $data['settings']['categoryShowcaseNo'];
    $categoryShowcaseDescription= $data['settings']['categoryShowcaseDescription'];
    
    $isCategoryBanner           = $data['settings']['isCategoryBanner'];
    $categoryBannerWidth        = $data['settings']['categoryBannerWidth'];
    $categoryBannerHeight       = $data['settings']['categoryBannerHeight'];
    
    $isCategoryImage            = $data['settings']['isCategoryImage'];
    $categoryWidth              = $data['settings']['categoryWidth'];
    $categoryHeight             = $data['settings']['categoryHeight'];
    $categoryThumbWidth         = $data['settings']['categoryThumbWidth'];
    $categoryThumbHeight        = $data['settings']['categoryThumbHeight'];
    
    $isBrandShowcase            = $data['settings']['isBrandShowcase'];
    $brandShowcaseTitle         = $data['settings']['brandShowcaseTitle'];
    $brandShowcaseNo            = $data['settings']['brandShowcaseNo'];
    $brandShowcaseDescription   = $data['settings']['brandShowcaseDescription'];
    
    $isBrandImage               = $data['settings']['isBrandImage'];
    $brandWidth                 = $data['settings']['brandWidth'];
    $brandHeight                = $data['settings']['brandHeight'];
    
    $isBanner                   = $data['settings']['isBanner'];
    $bannerWidth                = $data['settings']['bannerWidth'];
    $bannerHeight               = $data['settings']['bannerHeight'];
    
    $isGallery                  = $data['settings']['isGallery'];
    $imageWidth                 = $data['settings']['imageWidth'];
    $imageHeight                = $data['settings']['imageHeight'];
    $imageThumbWidth            = $data['settings']['imageThumbWidth'];
    $imageThumbHeight           = $data['settings']['imageThumbHeight'];

    $isShortDesc                = $data['settings']['isShortDesc'];
    $isButton                   = $data['settings']['isButton'];
    $btnText                    = $data['settings']['btnText'];
    $limit                      = $data['settings']['limit'];
    
    $isReview                   = $data['settings']['isReview'];
    $isWishlist                 = $data['settings']['isWishlist'];
    $isImageSlider              = $data['settings']['isImageSlider'];

	$isForm                     = $data['settings']['isForm'];
    $formHeading                = $data['settings']['formHeading'];
    $successMsg                 = $data['settings']['successMsg'];
    $isCaptcha                  = $data['settings']['isCaptcha'];
	
	$emailSubject               = $data['settings']['emailSubject'];
	$emailBody                  = $data['settings']['emailBody'];
	$toEmail                    = $data['settings']['toEmail'];
	$cc                         = $data['settings']['cc'];
	$bcc                        = $data['settings']['bcc'];
	$replyTo                    = $data['settings']['replyTo'];
    
    $isShowcase                 = $data['settings']['isShowcase'];
    
    $isSocial                   = $data['settings']['isSocial'];
}
else {
    
    $isCategoryShowcase         = $this->_request['isCategoryShowcase'];
    $categoryShowcaseTitle      = $this->_request['categoryShowcaseTitle'];
    $categoryShowcaseNo         = $this->_request['categoryShowcaseNo'];
    $categoryShowcaseDescription= $this->_request['categoryShowcaseDescription'];
    
    $isCategoryBanner           = $this->_request['isCategoryBanner'];
    $categoryBannerWidth        = $this->_request['categoryBannerWidth'];
    $categoryBannerHeight       = $this->_request['categoryBannerHeight'];
    
    $isCategoryImage            = $this->_request['isCategoryImage'];
    $categoryWidth              = $this->_request['categoryWidth'];
    $categoryHeight             = $this->_request['categoryHeight'];
    $categoryThumbWidth         = $this->_request['categoryThumbWidth'];
    $categoryThumbHeight        = $this->_request['categoryThumbHeight'];
    
    $isBrandShowcase            = $this->_request['isBrandShowcase'];
    $brandShowcaseTitle         = $this->_request['brandShowcaseTitle'];
    $brandShowcaseNo            = $this->_request['brandShowcaseNo'];
    $brandShowcaseDescription   = $this->_request['brandShowcaseDescription'];
    
    $isBrandImage               = $this->_request['isBrandImage'];
    $brandWidth                 = $this->_request['brandWidth'];
    $brandHeight                = $this->_request['brandHeight'];
    
    $isBanner                   = $this->_request['isBanner'];
    $bannerWidth                = $this->_request['bannerWidth'];
    $bannerHeight               = $this->_request['bannerHeight'];
    
    $isGallery                  = $this->_request['isGallery'];
    $imageWidth                 = $this->_request['imageWidth'];
    $imageHeight                = $this->_request['imageHeight'];
    $imageThumbWidth            = $this->_request['imageThumbWidth'];
    $imageThumbHeight           = $this->_request['imageThumbHeight'];

    $isShortDesc                = $this->_request['isShortDesc'];
    $isButton                   = $this->_request['isButton'];
    $btnText                    = $this->_request['btnText'];
    $limit                      = $this->_request['limit'];
    
    $isReview                   = $this->_request['isReview'];
    $isWishlist                 = $this->_request['isWishlist'];
    $isImageSlider              = $this->_request['isImageSlider'];
    
    $isForm                     = $this->_request['isForm'];
    $formHeading                = $this->_request['formHeading'];
    $successMsg                 = $this->_request['successMsg'];
    $isCaptcha                  = $this->_request['isCaptcha'];
    
    $emailSubject               = $this->_request['emailSubject'];
	$emailBody                  = $this->_request['emailBody'];
	$toEmail                    = $this->_request['toEmail'];
	$cc                         = $this->_request['cc'];
	$bcc                        = $this->_request['bcc'];
	$replyTo                    = $this->_request['replyTo'];
    
    $isShowcase                 = $this->_request['isShowcase'];
    
    $isSocial                   = $this->_request['isSocial'];
}
?>

<div class="container-fluid">
    <?php
    if($data['act']['message'])
        echo ($data['act']['type'] == 1)? '<div class="alert alert-success">'.$data['act']['message'].'</div>':'<div class="alert alert-danger">'.$data['act']['message'].'</div>';
    ?>
    
    <div>
        <form name="modifycontent" action="" method="POST">
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Category Showcase</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isCategoryShowcase" <?php if($isCategoryShowcase == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                            <span class="f-s-14 m-l-30" style="line-height:20px;">
                                Hook: showcase <span class="sweetBox" onclick="sAlert('Example', '<div>$this-&gt;hook(\'<?php echo $this->_request['pageType'];?>\', \'categoryShowcase\', array(\'css\'=&gt;\'product_list\', \'col\' =&gt; \'col-sm-4 col-xs-6\'));</div><hr><h3>Options</h3><ul style=\'text-align:left\'><li>\'wrapcss\' =&gt; CSS to wrap the section</li><li>\'css\' =&gt; CSS to wrap all items</li><li>\'col\' =&gt; CSS to indicate columns per row</li><li>\'slider\' =&gt; true (default value false)</li></ul>', true);"><i class="fa fa-question-circle"></i></span>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="categoryShowcaseTitle" value="<?php echo $categoryShowcaseTitle;?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Number of Items</label>
                                            <input type="text" name="categoryShowcaseNo" value="<?php echo $categoryShowcaseNo;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="categoryShowcaseDescription" class="form-control editor_small"><?php echo $categoryShowcaseDescription;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Category Banner</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isCategoryBanner" <?php if($isCategoryBanner == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Width [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="categoryBannerWidth" value="<?php echo $categoryBannerWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Height [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="categoryBannerHeight" value="<?php echo $categoryBannerHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Category Image</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isCategoryImage" <?php if($isCategoryImage == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="categoryWidth" value="<?php echo $categoryWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="categoryHeight" value="<?php echo $categoryHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Thumb Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="categoryThumbWidth" value="<?php echo $categoryThumbWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Thumb Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="categoryThumbHeight" value="<?php echo $categoryThumbHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Brand Showcase</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isBrandShowcase" <?php if($isBrandShowcase == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                            <span class="f-s-14 m-l-30" style="line-height:20px;">
                                Hook: showcase <span class="sweetBox" onclick="sAlert('Example', '<div>$this-&gt;hook(\'<?php echo $this->_request['pageType'];?>\', \'brandShowcase\', array(\'css\'=&gt;\'brand_list\', \'col\' =&gt; \'col-sm-4 col-xs-6\'));</div><hr><h3>Options</h3><ul style=\'text-align:left\'><li>\'wrapcss\' =&gt; CSS to wrap the section</li><li>\'css\' =&gt; CSS to wrap all items</li><li>\'col\' =&gt; CSS to indicate columns per row</li><li>\'slider\' =&gt; true (default value false)</li></ul>', true);"><i class="fa fa-question-circle"></i></span>
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-8">
                                        <div class="form-group">
                                            <label>Title</label>
                                            <input type="text" name="brandShowcaseTitle" value="<?php echo $brandShowcaseTitle;?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Number of Items</label>
                                            <input type="text" name="brandShowcaseNo" value="<?php echo $brandShowcaseNo;?>" class="form-control numbersOnly">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="brandShowcaseDescription" class="form-control editor_small"><?php echo $brandShowcaseDescription;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Brand Image</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isBrandImage" <?php if($isBrandImage == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="brandWidth" value="<?php echo $brandWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="brandHeight" value="<?php echo $brandHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Product Showcase</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isShowcase" <?php if($isShowcase == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                            <span class="f-s-14 m-l-30" style="line-height:20px;">
                                Hook: showcase <span class="sweetBox" onclick="sAlert('Example', '<div>$this-&gt;hook(\'<?php echo $this->_request['pageType'];?>\', \'showcase\', array(\'css\'=&gt;\'product_list sk_shadow_full\', \'col\' =&gt; \'col-sm-4 col-xs-6\', \'show\' =&gt; \'1\'));</div><hr><h3>Options</h3><ul style=\'text-align:left\'><li>\'wrapcss\' =&gt; CSS to wrap the section</li><li>\'css\' =&gt; CSS to wrap all items</li><li>\'col\' =&gt; CSS to indicate columns per row</li><li>\'slider\' =&gt; true (default value false)</li><li>\'show\' =&gt; 1 (showcase #)</li></ul>', true);"><i class="fa fa-question-circle"></i></span>
                            </span>
                        </div>
                        <div class="card-body showcaseWrap">
                            <span class="btn btn-info btn-sm moreShowcase">Add More Showcase</span>
                            <?php
                            $blankBox = '<legend>Showcase <span>1</span></legend><span class="deleteShowcase">&times;</span><div class="form-group"><div class="row"><div class="col-sm-8"><div class="form-group"><label>Title</label><input type="text" name="showcaseTitle[]" value="" class="form-control"></div></div><div class="col-sm-4"><div class="form-group"><label>Number of Items</label><input type="text" name="showcaseNo[]" value="" class="form-control numbersOnly"></div></div></div></div><div class="form-group"><label>Description</label><textarea name="showcaseDescription[]" class="form-control editor_small"></textarea></div>';

                            if(sizeof($data['settings']['showcase']) > 0) {
                                foreach($data['settings']['showcase'] as $key=>$showcase) {
                                    ?>
                                    <fieldset class="showcaseBox" data-id="<?php echo $key + 1;?>">
                                        <legend>Showcase <span><?php echo $key + 1;?></span></legend>
                                        <span class="deleteShowcase">&times;</span>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label>Title</label>
                                                        <input type="text" name="showcaseTitle[]" value="<?php echo $showcase['showcaseTitle'];?>" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label>Number of Items</label>
                                                        <input type="text" name="showcaseNo[]" value="<?php echo $showcase['showcaseNo'];?>" class="form-control numbersOnly">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Description</label>
                                            <textarea name="showcaseDescription[]" class="form-control editor_small"><?php echo $showcase['showcaseDescription'];?></textarea>
                                        </div>
                                    </fieldset>
                                    <?php
                                }
                            }
                            else
                                echo '<fieldset class="showcaseBox" data-id="1">'.$blankBox.'</fieldset>';
                            ?>
                        </div>
                    </div>
                    
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Social Buttons</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isSocial" <?php if($isSocial == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <?php if($data['sharescript']['socialSrc'] && $data['sharescript']['socialClass']) {?>
                        <div class="card-body">
                            <div class="alert alert-info">
                                <strong>Script SRC</strong><br>
                                &lt;script type="text/javascript" <br>src="<mark><?php echo $data['sharescript']['socialSrc'];?></mark>"&gt;&lt;/script&gt;
                                <hr>
                                <strong>Content Class</strong><br>
                                &lt;div class="<mark><?php echo $data['sharescript']['socialClass'];?></mark>"&gt;&lt;/div&gt;
                            </div>
                        </div>
                        <?php }?>
                    </div>
                </div>

                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Product Banner</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isBanner" <?php if($isBanner == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Width [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="bannerWidth" value="<?php echo $bannerWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-6 p-t-8 p-r-0 m-b-0">Height [px]</label>
                                <div class="col-sm-6">
                                    <input type="text" name="bannerHeight" value="<?php echo $bannerHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Gallery Image</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isGallery" <?php if($isGallery == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageWidth" value="<?php echo $imageWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Large Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageHeight" value="<?php echo $imageHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <hr>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Thumb Width [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageThumbWidth" value="<?php echo $imageThumbWidth;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Thumb Height [px]</label>
                                <div class="col-sm-5">
                                    <input type="text" name="imageThumbHeight" value="<?php echo $imageThumbHeight;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Item List</h4>
                        </div>
                        <div class="card-body">
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Short Description</label>
                                <div class="col-sm-5">
                                    <label class="switch float-right">
                                        <input type="checkbox" name="isShortDesc" <?php if($isShortDesc == '1') echo 'checked';?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Button</label>
                                <div class="col-sm-5">
                                    <label class="switch float-right">
                                        <input type="checkbox" name="isButton" <?php if($isButton == '1') echo 'checked';?>>
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Button Text</label>
                                <div class="col-sm-5">
                                    
                                    <input type="text" name="btnText" value="<?php echo $btnText;?>" class="form-control">
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-sm-7 p-t-8 m-b-0">Items per Page</label>
                                <div class="col-sm-5">
                                    
                                    <input type="text" name="limit" value="<?php echo ($limit) ? $limit : VALUE_PER_PAGE;?>" class="form-control numbersOnly">
                                </div>
                            </div>
                            
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title m-b-0">
                            <h4 style="line-height:24px;margin:0;">Review</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isReview" <?php if($isReview == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title m-b-0">
                            <h4 style="line-height:24px;margin:0;">Wish List</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isWishlist" <?php if($isWishlist == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-title m-b-0">
                            <h4 style="line-height:24px;margin:0;">Listing Image Slider</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isImageSlider" <?php if($isImageSlider == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-title">
                            <h4 style="line-height:24px;margin:0;">Quote Form</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isForm" <?php if($isForm == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Form Heading </label>
                                <input type="text" name="formHeading" value="<?php echo $formHeading;?>" placeholder="Form Heading" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Success Message </label>
                                <input type="text" name="successMsg" value="<?php echo $successMsg;?>" placeholder="Message" class="form-control">
                            </div>
                        </div>
                        <div class="card-title m-t-20 m-b-0">
                            <h4 style="line-height:24px;margin:0;">Google Recaptcha</h4>
                            <label class="switch float-right">
                                <input type="checkbox" name="isCaptcha" <?php if($isCaptcha == '1') echo 'checked';?>>
                                <span></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-8 contentL">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Email Subject *</label>
                                <input type="text" name="emailSubject" value="<?php echo $emailSubject;?>" placeholder="" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Email Template *</label>
                                <textarea name="emailBody" class="form-control editor_small"><?php echo $emailBody;?></textarea>
                            </div>
                            <div class="alert alert-info">Do not change these variables: {name}, {email}, {phone}, {product}, {comments}.</div>
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-4 contentS">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>To *</label>
                                <input type="text" name="toEmail" value="<?php echo $toEmail;?>" placeholder="Email Address" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Cc</label>
                                <input type="text" name="cc" value="<?php echo $cc;?>" placeholder="Email Address" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>Bcc</label>
                                <input type="text" name="bcc" value="<?php echo $bcc;?>" placeholder="Email Address" class="form-control">
                            </div>
                            
                            <div class="form-group">
                                <label>No-reply Email *</label>
                                <input type="text" name="replyTo" value="<?php echo $replyTo;?>" placeholder="Email Address" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <button type="button" name="Back" value="Back" onclick="location.href='index.php?pageType=<?php echo $this->_request['pageType'];?>&dtls=<?php echo $this->_request['dtls'];?>&moduleId=<?php echo $this->_request['moduleId'];?>'" class="btn btn-default m-r-15">Back</button>
                            
                            <input type="hidden" name="IdToEdit" value="<?php echo $IdToEdit;?>" />
                            <input type="hidden" name="SourceForm" value="addEditSettings" />
                            <button type="submit" name="Save" value="Save" class="btn btn-info login_btn">Save</button>

                            <button type="button" name="Cancel" value="Close" onclick="location.href='<?php echo SITE_ADMIN_PATH;?>'" class="btn btn-default m-l-15">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $('.moreShowcase').on('click', function(e){
            e.preventDefault();

            var lastId      = $(this).siblings('.showcaseBox:last-child').attr('data-id'),
                newId       = parseInt(lastId) + 1,
                blankBox    = '<fieldset class="showcaseBox" data-id="' + newId + '"><?php echo $blankBox;?></fieldset>';

            $(this).parent('.showcaseWrap').append(blankBox);
            $(this).siblings('.showcaseBox:last-child').find('legend span').text(newId);
            $(this).siblings('.showcaseBox:last-child').find('input').val('');
            $(this).siblings('.showcaseBox:last-child').find('textarea').html('');
        });
        
        $(document).on('click', '.deleteShowcase', function(e){
            e.preventDefault();

            var wrapper     = $(this).parents('.showcaseWrap'),
                blankBox    = '<fieldset class="showcaseBox" data-id="1"><?php echo $blankBox;?></fieldset>';

            if(wrapper.children().length <= 2) {
                wrapper.append(blankBox);
                wrapper.children('.showcaseBox:last-child').find('input').val('');
                wrapper.children('.showcaseBox:last-child').find('textarea').html('');
            }
            $(this).parent('.showcaseBox').remove();
        });
    });
</script>