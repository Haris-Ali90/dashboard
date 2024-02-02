<ul class="hoverable-dropdown-main-ul">
    @foreach($flagCategories as $category)
        @if($category->isFliterExist('is_show_on_route','0') && $category->isFliterExist('order_type','ecommerce') && $category->isFliterExist('portal','dashboard') && ( $category->isFliterExist('vendor_relation',$request_data['vendor_id']) || !$category->isFliterExist('vendor_relation')))
            <li>
                {{$category->category_name}}
                <?php $child_data = $category->getChilds->where('is_enable', 1); ?>
                @if(!$child_data->isEmpty())
                    <i class="fa fa-angle-right"></i>
                    <ul class="hoverable-dropdown-ul">
                        @foreach($child_data as $child)
                            <li data-id="{{$child->id}}" class="child-flag-cat">
                                {{$child->category_name}}
                                <?php $grand_child_data = $child->getChilds->where('is_enable', 1); ?>
                                @if(!$grand_child_data->isEmpty())
                                    <i class="fa fa-angle-right"></i>
                                    <ul class="hoverable-dropdown-ul">
                                        @foreach($grand_child_data as $grand_child)
                                            <li data-id="{{$grand_child->id}}" class="child-flag-cat can-apply-flag">
                                                {{$grand_child->category_name}}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </li>
        @endif
    @endforeach
</ul>
<div class="col-md-12 col-sm-12 col-xs-12 flag-order-details">
    <h2>Flag Order Detail</h2>
    <div class="table-responsive">
        <!--Table open for flag order details-->
        <table id="datatable" class="table table-bordered flag-order-details-table" >
            <thead>
            <tr>
                <th>ID</th>
                <th>Joey</th>
                <th>Flag By</th>
                <th>Flag Category Name</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody class="flag-model-history-tbl-body">
            @foreach($joey_flags_history as $key => $joey_flag_history)
                <tr class="flag-tr flag-tr-cat-bunch-{{$joey_flag_history->flag_cat_id}}">
                    <td>{{$key+1}}</td>
                    <td>
                        <input type="hidden"  value="{{$joey_flag_history->flag_cat_id}}">
                        @if(isset($joey_flag_history->joeyName->first_name))
                            {{$joey_flag_history->joeyName->first_name." ".$joey_flag_history->joeyName->last_name}}
                        @endif
                    </td>
                    <td>{{$joey_flag_history->flagByName->full_name}}</td>
                    <td>{{$joey_flag_history->flag_cat_name}}</td>
                    <td>{{ConvertTimeZone($joey_flag_history->created_at,'UTC','America/Toronto')}}</td>
                    <td>
                        @if($joey_flag_history->is_approved == 0)
                            <a href="{{ backend_url('un-flag/'.$joey_flag_history->id) }}" class="btn btn-danger" >Un Flag Order</a>
                        @else
                            <a class="btn-info btn-xs">Approved</a>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <!--Table close for flag order details-->
    </div>
</div>



