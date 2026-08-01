<div>

    <div class="container">
         <div class="row mt-5">

              <div class="col-md-12 panel-body table-responsive">
                    <!-- Search box -->
                    <input type="text" class="form-control" placeholder="Search Name or city" style="width: 250px;" wire:model="searchTerm" >

                    <!-- Paginated records -->
                    <table class="table">
                         <thead>
                              <tr>
                                  <th class="sort" wire:click="sortOrder('pass_no')">GATE PASS NO {!! $sortLink !!}</th>
                                  <th class="sort" wire:click="sortOrder('pass_date')">Date {!! $sortLink !!}</th>
                                  <th class="sort" wire:click="sortOrder('party_id')">Party ID {!! $sortLink !!}</th>
                               
                                  <th>Status</th>
                              </tr>
                         </thead>
                         <tbody>
                              @if ($gp_master->count())
                                   @foreach ($gp_master as $gp_masters)
                                        <tr>
                                            <td>{{$gp_masters->pass_no}}</td>
                                            <td>{{$gp_masters->pass_date}}</td>
                                            <td>{{$gp_masters->party_id}}</td>
                                          
                                        </tr>
                                   @endforeach
                              @else
                                   <tr>
                                        <td colspan="5">No record found</td>
                                   </tr>
                              @endif
                         </tbody>
                    </table>

                    <!-- Pagination navigation links -->
                    {{$gp_master->links()}}
              </div>

         </div>
    </div>

</div>

