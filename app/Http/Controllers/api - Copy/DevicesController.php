<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use App\Models\Devices;
use App\Models\Company;
use DB;
use Validator;

class DevicesController extends Controller
{
    public function index()
    {
        //
    }

    /**
 * @OA\Get(
 *     path="/api/devices/get",
 *     operationId="getDevices",
 *     tags={"Devices"},
 *     summary="Get a list of all devices",
 *     description="Retrieve all devices from the database.",
 *     @OA\Response(
 *         response=200,
 *         description="Devices retrieved successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Success"),
 *             @OA\Property(property="data", type="array", @OA\Items(
 *                 @OA\Property(property="device_name", type="string", example="Device 1"),
 *                 @OA\Property(property="device_uid", type="string", example="UUID123"),
 *                 @OA\Property(property="status", type="string", example="active")
 *             ))
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="error", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */

    public function get(Request $request)
    {	//	\DB::enableQueryLog(); 
        if ($request->has('id')) {
			$res = Devices::where('device_id', $request->get('id'))->first();
		}else if ($request->has('name')) {
			$res = Devices::where('device_name', 'like', '%' . $request->get('name') . '%')->get();
		}else{
            $res = DB::table('devices')->where('status', 'A')->get();
        }
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
	
    /* public function get()
    {
        $response = DB::table('devices')->get();
        return response()->json([
            'status' => 200,
            'message' => 'Success',
            'data' => $response,
        ], 200);
    } */

    /**
 * @OA\Post(
 *     path="/api/devices/store",
 *     operationId="storeDevice",
 *     tags={"Devices"},
 *     summary="Create a new device",
 *     description="Store a new device in the database.",
 *     @OA\RequestBody(
 *         required=true,
 *         description="Device data to be created",
 *         @OA\JsonContent(
 *             required={"device_name", "status"},
 *             @OA\Property(property="device_name", type="string", example="Device 1"),
 *             @OA\Property(property="device_uid", type="string", example="UUID123"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Device created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Device created successfully."),
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed or unique constraint violated",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="error", type="string", example="Device number must be unique. This device number is already taken."),
 *             @OA\Property(property="details", type="string", example="Integrity constraint violation")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="error", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */

   public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'device_name' => 'required',
                'status' => 'required',
            ]);

            $device = Devices::create([
                'device_name' => $validated['device_name'],
                'device_uid' => $request->device_uid,
                'created_by' => $request->created_by,
                'status' => $validated['status'],
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Device created successfully.',
                'data' => $device,
            ], 200);
        } catch (QueryException $e) {
            if ($e->getCode() === '23000') {
                return response()->json([
                    'status' => 400,
                    'error' => 'Devices number must be unique. This device number is already taken.',
                    'details' => $e->getMessage(),
                ], 400);
            }

            return response()->json([
                'status' => 500,
                'error' => 'An error occurred while processing your request.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }

/**
 * @OA\Put(
 *     path="/api/devices/{id}/update",
 *     operationId="updateDevice",
 *     tags={"Devices"},
 *     summary="Update a specific device",
 *     description="Update the device details in the database.",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the device to update",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Device data to be updated",
 *         @OA\JsonContent(
 *             required={"device_name", "status"},
 *             @OA\Property(property="device_name", type="string", example="Device 1"),
 *             @OA\Property(property="device_uid", type="string", example="UUID123"),
 *             @OA\Property(property="status", type="string", example="active")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Device updated successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=200),
 *             @OA\Property(property="message", type="string", example="Device updated successfully.")
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Device not found",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=404),
 *             @OA\Property(property="message", type="string", example="Device not found!")
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad Request - Validation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=400),
 *             @OA\Property(property="errors", type="object")
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal Server Error",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="integer", example=500),
 *             @OA\Property(property="error", type="string", example="An error occurred while processing your request."),
 *             @OA\Property(property="details", type="string", example="Database connection failed")
 *         )
 *     )
 * )
 */

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'device_name' => 'required',
            'status' => 'required|string|max:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $device = Devices::find($id);

        if ($device) {
            $device->device_uid = $request->device_uid;
            $device->device_name = $request->device_name;
            $device->status = $request->status;
            $device->updated_by = $request->updated_by;
            $device->save();

            return response()->json([
                'status' => 200,
                'message' => 'Device updated successfully.',
            ], 200);
        }

        return response()->json(['message' => 'device not found!'], 404);
    }


	/**
	 * @OA\Delete(
	 *     path="/api/devices/{id}",
	 *     summary="Delete a device",
	 *     description="Marks a device as inactive by setting its status to 'I'.",
	 *     operationId="destroyDevice",
	 *     tags={"Devices"},
	 *     @OA\Parameter(
	 *         name="id",
	 *         in="path",
	 *         required=true,
	 *         description="ID of the device to be deleted",
	 *         @OA\Schema(type="string")
	 *     ),
	 *     @OA\Response(
	 *         response=200,
	 *         description="Device marked as inactive successfully",
	 *         @OA\JsonContent(
	 *             @OA\Property(
	 *                 property="status",
	 *                 type="integer",
	 *                 example=200
	 *             ),
	 *             @OA\Property(
	 *                 property="message",
	 *                 type="string",
	 *                 example="Device Deleted successfully."
	 *             )
	 *         )
	 *     ),
	 *     @OA\Response(
	 *         response=404,
	 *         description="Device not found",
	 *         @OA\JsonContent(
	 *             @OA\Property(
	 *                 property="message",
	 *                 type="string",
	 *                 example="Device not found!"
	 *             )
	 *         )
	 *     )
	 * )
	 */

    public function destroy(string $id)
    {
        $device = Devices::find($id);

        if ($device) {
            $device->status = "I";
            $device->save();

            return response()->json([
                'status' => 200,
                'message' => 'Device Deleted successfully.',
            ], 200);
        }

        return response()->json(['message' => 'Device not found!'], 404);
    }
	
	public function getCompany(Request $request)
    {	//	\DB::enableQueryLog(); 
        if ($request->has('id')) {
			$res = Company::where('id', $request->get('id'))->first();
		}else if ($request->has('name')) {
			$res = Company::where('name', 'like', '%' . $request->get('name') . '%')->get();
		}else{
            $res = DB::table('company')->where('status', 'A')->get();
        }
		// $qry = \DB::getQueryLog();
		return response(['status' => 200, 'result' => "true", 'message' => "Success", 'data'=>$res]);
    }
	
}
