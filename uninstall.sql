-- add table prefix if you have one
DELETE FROM core_config_data WHERE path like 'ffuenf_childproductcategories/%';
DELETE FROM core_resource WHERE code = 'ffuenf_childproductcategories_setup';