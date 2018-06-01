<div class="content-padding dis-flex flex-col pd10 new-station wh1">
    <div class="content-header">
        <!--<div>-->
        <h5 class="pb10" style="border-bottom: 1px solid #cccccc">编辑子网</h5>
        <!--</div>-->
    </div>
    <div class="flex-1 flex" style="overflow: auto;box-sizing: border-box;">
        <div class="flex flex-col">
            <!--<div class="wh1 ht1">-->
            <div class="pd10">
                <div class=" mr10" style="display:inline-block;"><span>子网名称:</span> <input style="display:inline-block;width:auto;" type="text" name="subNetName" value="" placeholder="请输入20个字符以内"
                                                                                           class="subNetName form-control ng-pristine ng-untouched ng-valid"
                                                                                           ng-model="subNetName" ng-minlength="41" ng-maxlength="20" ng-required="true" ng-value="edit.subNetName">
                </div>
                <div class="subNetName-msg"></div>
                <div class=" mr10" style="display:inline-block;"><span>子概述:</span> <input style="display:inline-block;width:auto;" type="text" name="describe" value="" placeholder="请输入40个字符以内" class="describe form-control ng-pristine ng-untouched ng-valid"
                                                                                          ng-model="describe" ng-minlength="1" ng-maxlength="40" ng-value="edit.describe">

                </div>
                <div class="describe-msg"></div>

            </div>
            <div>
                <span class=" mr3">所在省</span>
                <select style="width: 90px"
                        ng-model="subnetEdit.defaultProvince"
                        class="form-control"
                        ng-change="subnetEdit.cityData(subnetEdit.defaultProvince)"
                        ng-options="item.areaCode as item.areaName for item in subnetEdit.provinceList">
                    <option value="">全部</option>
                </select>

                <span class="mr3">所在市</span>
                <select style="width: 90px"
                        ng-model="subnetEdit.defaultCity"
                        class="form-control"
                        ng-change="subnetEdit.getCityCodeSearch(subnetEdit.defaultCity)"
                        ng-options="item.areaCode as item.areaName for item in subnetEdit.cityList">
                    <option value="">全部</option>
                </select>
                <!--<input type="text">-->
                <div class="form-group " style="display:inline-block;">
                    <input name="stnCode" ng-model="subnetEdit.stnCode" type="text" placeholder="请输入基准站ID/名称"
                           class="stnCode form-control ng-pristine ng-untouched ng-valid" />
                </div>
                <button type="submit" class="btn btn-default btn-sm " ng-click="subnetEdit.search()"  class="btn btn-default btn-sm">查询</button>
                <button ng-click="subnetEdit.autoCreateNet()"   class="btn btn-info ">自动组网</button>
                <button ng-click="subnetEdit.createNet()"  class="btn btn-info ">手动调整</button>
            </div>
            <div class="flex-1 flex flex-row">
                <div class="" style="width:100%;">

                    <div class="wh-300 ht-450 br-ccc pd20" style="float:right;height:617px;">
                        <p>备选基准站
                            <button ng-click="subnetCreate.selectAll()" class="btn  btn-info">全选</button>
                        </p>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="width:167px;">基准站名称</th>
                                    <th>ID</th>

                                </tr>
                            </thead>
                        </table>
                        <div class="tablebox tt-scl" style="">
                            <table width="100%">
                                <tr ng-repeat="item in subnetEdit.subnetlist">
                                    <td><input type="checkbox" name="item.stnId" ng-click="subnetEdit.ckFun(item,$event)"
                                               ng-checked="subnetEdit.checkStatus" value="{{item.stnId}}"
                                               nat_name="{{item.stnName}}" lat="{{item.lat02}}" lon="{{item.lon02}}"></td>
                                    <!--<td><input type="checkbox" id={{tag.id}} name="{{tag.name}}" ng-checked="isSelected(tag.id)" ng-click="updateSelection($event,tag.id)"></td>-->
                                    <td>{{item.stnName}}</td>
                                    <td>{{item.stnCode}}</td>
                                </tr>
                            </table>
                        </div>
                        <button class="mt10 btn  btn-info" ng-click="subnetEdit.addFun()">add</button>
                        <button class="mt10 btn  btn-info" ng-click="subnetEdit.deleFun()">dele</button>
                        <br />
                        <br />

                        <p>备选基准站
                            <button ng-click="subnetCreate.selectAll1()" class="btn  btn-info">全选</button>
                        </p>
                        <table width="100%">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th style="width:167px;">基准站名称</th>
                                    <th>ID</th>

                                </tr>
                            </thead>

                        </table>
                        <div class="tt-scl1">
                            <table width="100%">
                                <tr ng-repeat="item in edit.subNetStns">
                                    <td><input type="checkbox" name="item.stnId" ng-click="subnetEdit.ckFun(item,$event)"
                                               ng-checked="item.stnId" value="{{item.stnId}}"
                                               nat_name="{{item.stnName}}" lat="{{item.lat02}}" lon="{{item.lon02}}"></td>
                                    <!--<td><input type="checkbox" id={{tag.id}} name="{{tag.name}}" ng-checked="isSelected(tag.id)" ng-click="updateSelection($event,tag.id)"></td>-->
                                    <td>{{item.stnName}}</td>
                                    <td>{{item.stnCode}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div style="overflow:hidden;width:auto;height:100%;">
                        <div class="flex-1 ht1"  mapbar-map center="北京市" level=7  map-id="subnetmap"></div>
                    </div>


                </div>


            </div>

            <div class="form-group" style="margin-right:300px;margin-top:20px;">
                <div class="col-sm-8 col-sm-offset-3" style="margin-left:0;text-align:center;width:100%;">
                    <button class="btn btn-primary" type="submit" style="width: 85px;margin-right:15px;" ng-click="subnetCreate.submit()">保存
                    </button>
                    <button class="btn btn-default" type="reset"
                            style="width: 85px;" wd-tab-view-ref="main.subNet">取消
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  (function() {
        $('.subNetName-msg').hide();
        $('.describe-msg').hide();
        $(".subNetName").blur(function(){
            var subNetName = $(this).val();
            if (!subNetName) {
                $('.subNetName-msg').show().text('子网名称必须填写');
            } else {
                if (subNetName.length > 20) {
                    $('.subNetName-msg').show().text('子网名称不能超过20个字符');
                } else {
                    $('.subNetName-msg').hide();
                }
            }
        });

        $(".describe").blur(function(){
            var describe = $(this).val();
            if (!describe) {
                $('.describe-msg').show().text('子网描述必须大于1个字符');
            } else {
                if (subNetName.length > 40) {
                    $('.describe-msg').show().text('子网描述不能超过40个字符');
                } else {
                    $('.describe-msg').hide();
                }
            }
        });
  })();
</script>
