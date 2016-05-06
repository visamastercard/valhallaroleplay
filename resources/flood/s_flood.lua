local startTick
local targetLevel = 20
local duration = 3600000

addEventHandler('onResourceStart', resourceRoot,
	function()
		createWater(-2998, -2998, 0, 2998, -2998, 0, -2998, 2998, 0, 2998, 2998, 0)
		startTick = getTickCount()
	end
)

addEvent('onPlayerReady', true)
addEventHandler('onPlayerReady', resourceRoot,
	function()
		local passed = getTickCount() - startTick
		if passed > duration then
			passed = duration
		end
		triggerClientEvent(client, 'doSetWaterLevel', resourceRoot, targetLevel * (passed/duration))
	end
)