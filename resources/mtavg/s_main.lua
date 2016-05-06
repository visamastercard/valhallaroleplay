-- addaccount: Added account 'website' with password 'dsf8h837aghr97g897 ewgf9g97gf'
function reloadUserPerks( userID )
	if (userID) then
		local found = false
		local foundElement = nil
		for key, value in ipairs(exports.pool:getPoolElementsByType("player")) do
			local accid = tonumber(getElementData(value, "account:id"))
			if (accid) then
				if (accid==tonumber(userID)) then
					found = true
					foundElement = value
					break
				end
			end
		end
		
		if (found) then
			exports.donators:loadAllPerks(foundElement)
			outputDebugString("->call('reloadUserPerks', '".. tostring(userID) .."')=200 OK")
		else
			outputDebugString("->call('reloadUserPerks', '".. tostring(userID) .."')=402 USER NOT ONLINE")
		end
	end
end

function isPlayerOnline( userID )
	local found = false
	if (userID) then
		
		for key, value in ipairs(exports.pool:getPoolElementsByType("player")) do
			local accid = tonumber(getElementData(value, "account:id"))
			if (accid) then
				if (accid==tonumber(userID)) then
					found = true
					break
				end
			end
		end
	end
	return found
end

function deleteItem(itemID, itemValue)
	if not (itemID) then
		return false
	end
	
	if not (itemValue) then
		return false
	end
	
	exports['item-system']:deleteAll(itemID, itemValue)
end

function statTransfer(userID, fromCharacterID, toCharacterID)
	if (userID) and (fromCharacterID) and (toCharacterID) then
		local fromCharacterName = exports.cache:getCharacterName(fromCharacterID)
		local toCharacterName = exports.cache:getCharacterName(toCharacterID)
		exports.global:sendMessageToAdmins("AdmWrn: User "..tostring(userID).." transfered perks from " .. fromCharacterName .. " to ".. toCharacterName)
		
		-- reload vehicles
		for key, value in ipairs(exports.pool:getPoolElementsByType("vehicle")) do
		local owner = getElementData(value, "owner")

			if (owner) then
				if (tonumber(owner)==tonumber(fromCharacterID)) or (tonumber(owner)==tonumber(toCharacterID)) then
					local id = getElementData(value, "dbid")
					outputDebugString("* Reloading vehicle ".. tostring(id))
					exports['vehicle-system']:reloadVehicle(id)
				end
			end
		end
		
		-- reload interiors
		for key, value in ipairs( getElementsByType("interior") ) do
			local interiorStatus = getElementData(value, "status")
			local owner = interiorStatus[4]
			if (owner) then
				if (tonumber(owner)==tonumber(fromCharacterID)) or (tonumber(owner)==tonumber(toCharacterID)) then
					local id = getElementData(value, "dbid")
					outputDebugString("* Reloading interior ".. tostring(id))
					exports['interior-system']:realReloadInterior(id)
				end
			end	
		end
		outputDebugString("* Stat transfer processed")
	end
end

function retrieveWeaponDetails(serialNumber)
	return exports.global:retrieveWeaponDetails( serialNumber )
end