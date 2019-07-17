select 
			case
				when  (DATE_FORMAT(calldate, '%d')) between 1 and 10 then '1 al 10'
				when  (DATE_FORMAT(calldate, '%d')) between 11 and 31 then '11 al 31'
			end as tramo,	
			COUNT(calldate) as llamadas,
			sum(segundo) as segundos
from CARGOACCESO 
					where    (ido=322)  and 
								((DATE_FORMAT(calldate, '%m')) = 8) and
								(((DATE_FORMAT(calldate, '%d')) between 1 and 10) or  ((DATE_FORMAT(calldate, '%d')) between 11 and 31)) and 
								((DATE_FORMAT(calldate, '%H:%i:%s')) between '09:00:00' and '23:59:59') and
								
								(DATE_FORMAT(calldate,'%Y-%m-%d') not in (select fecha from FERIADO)) and
								(dayname(calldate) ='Monday' or 
								dayname(calldate) ='Tuesday' or
								dayname(calldate) ='Wednesday' or
								dayname(calldate) ='Thursday' or 
								dayname(calldate) ='Friday')								
					group by tramo; 		
		
					
					
					
					
select 
			case
				when  (DATE_FORMAT(calldate, '%d')) between 1 and 10 then '1 al 10'
				when  (DATE_FORMAT(calldate, '%d')) between 11 and 31 then '11 al 31'
			end as tramo,	
			
			COUNT(calldate) as llamadas,
			
			sum(segundo) as segundos
from CARGOACCESO
						where 	(ido=322)  and
									((DATE_FORMAT(calldate, '%m')) = 8) and
									(((DATE_FORMAT(calldate, '%d')) between 1 and 10) or  ((DATE_FORMAT(calldate, '%d')) between 11 and 31)) and 
									((DATE_FORMAT(calldate, '%H:%i:%s')) between '09:00:00' and '23:59:59') and
																
									(DATE_FORMAT(calldate,'%Y-%m-%d') in (select fecha from FERIADO)) or
									(dayname(calldate) = 'Saturday' or
									dayname(calldate) = 'Sunday')
						group by tramo; 
									
									
									
									
select 
			case
				when  (DATE_FORMAT(calldate, '%d')) between 1 and 10 then '1 al 10'
				when  (DATE_FORMAT(calldate, '%d')) between 11 and 31 then '11 al 31'
			end as tramo,	
			
			COUNT(calldate) as llamadas,
			
			sum(segundo) as segundos
from CARGOACCESO 
					where    (ido=322)  and 
								((DATE_FORMAT(calldate, '%m')) = 8) and
								(((DATE_FORMAT(calldate, '%d')) between 1 and 10) or  ((DATE_FORMAT(calldate, '%d')) between 11 and 31)) and 
								((DATE_FORMAT(calldate, '%H:%i:%s')) between '00:00:00' and '08:59:59')								
					group by tramo; 											
									