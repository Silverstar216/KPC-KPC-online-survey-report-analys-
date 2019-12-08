<?php
$site_url = site_url();
?>
		<input type="hidden" id="gtotal" value="<?=$totalCnt?>" >
		<table class="search_t2">
			<tr>
				<th style="width:10%"><input type="checkbox" id="chkall"></th>
				<th style="width:35%">그룹명</th>
				<th style="width:10%">개수</th>
				<th style="width:35%">이동</th>
				<th style="width:10%">보기</th>
			</tr>
			<?php foreach ($groupCont as $item):?>           
			<tr>
				<td><input type="checkbox" id="<?=$item['id']?>" ></td>
				<td><input type="text" id="gn_<?=$item['id']?>" name="st_val" value="<?=$item['name']?>" style="width:80%;border: 1px solid #e3e3e3;"></td>
				<td><?=$item['cnt']?></td>
				<td>
				<select class="groupExChange"  id="slt_<?=$item['cnt']?>">
					<option value=""></option>
					<?php foreach ($groups as $oitem):?>
						<?php if(strcmp($item['name'], $oitem['name']) !== 0):?>
								<option  value="<?=$item['id']?>_<?=$oitem['id']?>"><?=$oitem['name']?></option>
						 <?php endif; ?>
					 <?php endforeach;?>  
				</select>
			</td>
				<td><img class="phonecursorImg" src="<?=$site_url?>images/btn/btn_view.png" id="c_<?=$item['id']?>" /></td>
			</tr>
           <?php endforeach;?>			
		</table>
		