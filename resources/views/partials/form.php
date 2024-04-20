<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="nume"><?=$fnume;?></label>
		<input class="form-control" type="text" id="nume" name="nume" placeholder="<?=$fnume;?>" autocomplete="off">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="pren"><?=$fprenume;?></label>
		<input class="form-control" type="text" id="pren" name="pren" placeholder="<?=$fprenume;?>" autocomplete="off">
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="companie"><?=$fcompanie;?></label>
		<input class="form-control" type="text" id="companie" name="companie" placeholder="<?=$fcompanie;?>">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="func"><?=$ffunctie;?></label>
		<input class="form-control" type="text" id="func" name="func" placeholder="<?=$ffunctie;?>" autocomplete="off">
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="dom"><?=$fdomeniu;?></label>
		<input class="form-control" type="text" id="dom" name="dom" placeholder="<?=$fdomeniu;?>" autocomplete="off">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="email"><?=$femail;?></label>
		<input class="form-control" type="text" id="email" name="email" placeholder="<?=$femail;?>" autocomplete="off">
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="email"><?=$ftara;?></label>
		<select class="form-control" id="tara" name="tara" required>
			<option value="--" disabled selected><?=$ftara;?></option>
		</select>
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="email"><?=$fjudet;?></label>
		<select class="form-control" id="jud" name="jud" required>
			<option value="" disabled selected><?=$fjudet;?></option>
		</select>
	</div>
</div>
<div class="form-row">
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="loca"><?=$flocalitate;?>address</label>
		<input class="form-control" type="text" id="loca" name="loca" placeholder="<?=$flocalitate;?>">
	</div>
	<div class="col-12 col-md-6 pb-3">
		<label class="form-label" for="tel"><?=$ftelefon;?></label>
		<input class="form-control" type="text" id="tel" name="tel" placeholder="<?=$ftelefon;?>">
	</div>
</div>

<div class="form-row pb-3">
	<div class="col">
		<label class="form-label" for="cumati"><?=$fcumatiaflat;?></label>
		<input class="form-control" type="text" id="cumati" name="cumati" placeholder="<?=$fcumatiaflat;?>" autocomplete="off">
		<input class="form-control" type="hidden" id="country" name="country" autocomplete="off">
	</div>
</div>

<div class="form-row pb-3">
	<div class="col">
		<label class="form-label" for="mesaj"><?=$fdetalii;?></label>
		<textarea class="form-control" rows="3" id="mesaj" name="mesaj" placeholder="<?=$fdetalii;?>"></textarea>
	</div>
</div>
<div class="form-row pb-3">
	<div class="col">
		<div class="p-2 check-bg">
			<div class="form-check form-check-inline">
				<label class="form-check-label form-newsletter">						
					<input class="form-check-input form-checkbox-min" type="checkbox" id="newsletter" name="newsletter" checked>
					<?=$fnewsletter;?>
					<a href="<?=THEMEURL;?>gdpr" target="_blank"><strong><?=$gdpr_conf;?></strong></a>
				</label>
			</div>
		</div>
	</div>
</div>