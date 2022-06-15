<div class="Polaris-Page" style="max-width: 60%;">
    <div class="Polaris-Page-Header">
        <div class="Polaris-Page-Header__MainContent">
            <div class="Polaris-Page-Header__TitleActionMenuWrapper">
                <div>
                    <div class="Polaris-Header-Title__TitleAndSubtitleWrapper">
                        <div class="Polaris-Header-Title">
                            <h1 class="Polaris-DisplayText Polaris-DisplayText--sizeLarge">Products list</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="Polaris-SkeletonPage__Page" style="max-width: 100%;padding: 0;">
        <div class="Polaris-Page__Content">
            <div class="Polaris-Card">
                <div class="Polaris-DataTable common_datatable_page">
                    <div class="Polaris-DataTable__ScrollContainer">
                        <table class="Polaris-DataTable__Table">
                            <thead>
                                <tr>
                                    <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--total Polaris-DataTable__Cell--numeric sorting_1" scope="col">Name</th>
                                    <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--total Polaris-DataTable__Cell--numeric sorting_1" scope="col">Status</th>
                                    <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--total Polaris-DataTable__Cell--numeric sorting_1" scope="col">Vendor</th>
                                    <th class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--total Polaris-DataTable__Cell--numeric sorting_1" scope="col">Product Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    for($i=0; $i<=count($shopDatas->products); $i++) {
                                ?>
                                    <tr>
                                        <td data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric sorting_desc" scope="col"> <?php if(isset($shopDatas->products[$i]->title)) { echo $shopDatas->products[$i]->title; }else { echo '-'; } ?> </td>
                                        <td data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric sorting_desc" scope="col"> <?php if(isset($shopDatas->products[$i]->status)) { echo $shopDatas->products[$i]->status; }else { echo '-'; } ?> </td>
                                        <td data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric sorting_desc" scope="col"> <?php if(isset($shopDatas->products[$i]->vendor)) { echo $shopDatas->products[$i]->vendor; }else { echo '-'; } ?> </td>
                                        <td data-polaris-header-cell="true" class="Polaris-DataTable__Cell Polaris-DataTable__Cell--verticalAlignTop Polaris-DataTable__Cell--header Polaris-DataTable__Cell--numeric sorting_desc" scope="col"> <?php if(isset($shopDatas->products[$i]->product_type)) { echo $shopDatas->products[$i]->product_type; }else { echo '-'; } ?> </td>
                                    </tr>
                                    <?php break; } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
jQuery('#customerListing').DataTable();
</script>
