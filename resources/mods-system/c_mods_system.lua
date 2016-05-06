function applyMods()
	----------------------
	-- Pig Pen Interior --
	----------------------
	--[[ Bar
	pigpen1 = engineLoadTXD("ls/lee_stripclub1.txd")
	engineImportTXD(pigpen1, 14831)
	
	-- corver stage + seat
	pigpen2 = engineLoadTXD("ls/lee_stripclub.txd")
	engineImportTXD(pigpen2, 14832)
	-- Backwall seats
	engineImportTXD(pigpen2, 14833)
	-- columns
	engineImportTXD(pigpen2, 14835)
	-- corner seats
	engineImportTXD(pigpen2, 14837)
	-- main interior structure
	engineImportTXD(pigpen2, 14838)
	]]

	-- Bus stops
	busStop = engineLoadTXD("ls/bustopm.txd")
	engineImportTXD(busStop, 1257)
			
	-- Skin model 9 to a female cop
	skin9 = engineLoadTXD("mods/skins/9/wfyst.txd")
	engineImportTXD(skin9, 9)		
	----------------
	-- Gang Tags --
	----------------
	--tag3 = engineLoadTXD ( "tags/tags_larifa.txd" ) -- Crips
	--engineImportTXD ( tag3, 1526 )

	--tag4 = engineLoadTXD ( "tags/tags_larollin.txd" ) -- LD
	--engineImportTXD ( tag4, 1527 )

	--tag8 = engineLoadTXD ( "tags/tags_laazteca.txd" ) -- ESS
	--engineImportTXD ( tag8, 1531 )
	
end
addEventHandler("onClientResourceStart", getResourceRootElement(), applyMods)