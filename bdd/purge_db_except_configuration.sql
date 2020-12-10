delete from bp_info_product;
delete from bp_info_charge;
delete from bp_exercice;
delete from bp_charge;
DELETE from bp_bfr;
DELETE from bp_information;
DELETE from bp_investissement;
DELETE from bp_produit;
DELETE from bp_saisonnalite;
DELETE from bp_staff;
DELETE from join_cart_catalog;
DELETE from bp_cart;
DELETE from bp_funding;
DELETE from bp_bp;
DELETE FROM bp_service;

delete bp_address from bp_address left join user_user on bp_address.user_id=user_user.id where username not like '%trey%';
delete from bp_address where user_id is null;
delete from payment;
delete from user_user where username not like '%trey%';

delete from promotion_coupon;
delete from back_contact;