<style type="text/css">
  .clearfix {
    clear: both;
  }
  .single-staff {
    margin-bottom: 50px;
  }
  .single-staff .photo {
    float: left;
    margin-right: 15px;
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
  .single-staff .bio {
    margin-bottom: 8px;
  }
  .single-staff .email {
    font-size: .9em;
    line-height: .9em;
    margin-bottom: 10px;
  }
  .single-staff .phone {
    font-size: .9em;
    line-height: .9em;
  }
  .single-staff .website {
    font-size: .9em;
    line-height: .9em;
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
            <div class="bio">
                [bio]
            </div>
                [email]
                [phone_number]
                [website]
            <div class="clearfix"></div>
        </div>

    [/staff_loop]

</div>
