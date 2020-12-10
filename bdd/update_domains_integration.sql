UPDATE partners_partner SET customDomain = REPLACE(customDomain, 'prod1.', '');
UPDATE partners_partner SET customDomain = REPLACE(customDomain, 'planoo.ai', 'integration.planoo.dev');


INSERT INTO promotion_coupon (id,partner_id,code,name,used,maxUsed,starts_at,ends_at,minimumAmount,couponRange,kind,couponValue,sentDate) VALUES (NULL, '2', 'TESTLEGALSTART', 'TESTLEGALSTART', '0', '100', '2019-12-23 00:00:00', NULL, NULL, 'BP_ONLY', 'AMOUNT', '119', NULL);