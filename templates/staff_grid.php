<style type="text/css">
  .clearfix {
    clear: both;
  }
  .single-staff {
    float: left;
    width: 25%;
    text-align: center;
    padding: 0px 10px;
  }
  .single-staff .photo {
    margin-bottom: 5px;
  }
  .single-staff .photo img {
    max-width: 100px;
    height: auto;
  }
  .single-staff .name {
    font-size: 1em;
    line-height: 1em;
    margin-bottom: 4px;
  }
  .single-staff .position {
    font-size: .9em;
    line-height: .9em;
    margin-bottom: 10px;
  }
</style>
<div id="staff-directory-wrapper">

    [staff_loop]

        <div class="single-staff">
                [photo]
            <div class="name">
                [name]
            </div>
            <div class="position">
                [position]
            </div>
        </div>

    [/staff_loop]

</div>
