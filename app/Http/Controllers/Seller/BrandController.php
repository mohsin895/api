<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BrandDocument;
use App\Models\SellerBrand;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public $image_files = array();

    public function getBrandList(Request $request)
    {
    	$query=SellerBrand::with('brands','seller','SellerBrandRejections','category')->orderBy('id','desc');
        
    

        if(isset($request->name) && !is_null($request->name)){
            $query->where(function($q) use($request){
                $q->where('name','like',$request->name.'%');
                        
            });
        }

        $dataList=$query->paginate($request->numOfData);

        return response()->json($dataList);
    }
    public function addBrand(Request $request)
    {
        try{
            DB::beginTransaction();

          
            $title=$request->input('name');
            $exists =Brand::where('name',$title)->first();
            if($exists){

                $dataInfo = Brand::where('id',$exists->id)->first();
                $dataInfo->seller_id=Auth::guard('seller-api')->user()->id;

                $dataInfo->save();
                if( $this->sellerInfo($request,$dataInfo)){
                    DB::commit();
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>"Successfully Added Brand.",
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
                }
                else{

                    DB::rollBack();

                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Product.',
                     ];

                    return response()->json($responseData,200);
                }


            }else{
                $dataInfo=new Brand();

                   $dataInfo->name=$request->name;
                   $dataInfo->seller_id=Auth::guard('seller-api')->user()->id;
      
                   $dataInfo->meta_key_word=$request->name;
   
                   $dataInfo->meta_title=$request->name;
       
                   $dataInfo->meta_description=$request->name;
                   $dataInfo->addedBy=2;
                   $dataInfo->slug=Str::slug($request->name);
   
   
                   $dataInfo->created_at=Carbon::now();
                   $dataInfo->save();
                   if($dataInfo->save()){
                   
              
                    if( $this->sellerInfo($request,$dataInfo)){
                        DB::commit();
                        $responseData=[
                            'errMsgFlag'=>false,
                            'msgFlag'=>true,
                            'msg'=>"Successfully Added Brand.",
                            'errMsg'=>null,
                         ];
    
                        return response()->json($responseData,200);
                    }
                    else{
    
                        DB::rollBack();
    
                        $responseData=[
                            'errMsgFlag'=>true,
                            'msgFlag'=>false,
                            'msg'=>null,
                            'errMsg'=>'Failed To Add Product.',
                         ];
    
                        return response()->json($responseData,200);
                    }
                }
                else{
                    
                    DB::rollBack();
    
                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Product.',
                     ];
    
                    return response()->json($responseData,200);
                }

                

            }
            
           

        }
        catch(\Exception $err){
           $responseData=[
                    'errMsgFlag'=>true,
                    'msgFlag'=>false,
                    'msg'=>null,
                    'errMsg'=>'Something Went Wrong.Please Try Again.',
                 ];

            return response()->json($responseData,200); 
        }
    }
    public function getBrandInfo(Request $request)
    {
    	$dataInfo=SellerBrand::with('brands','seller','BrandDocuments','BrandRejections','category')->where('id',$request->dataId)->first();
        
        if(!empty($dataInfo)) {
            $responseData=[
                  'errMsgFlag'=>false,
                  'msgFlag'=>true,
                  'errMsg'=>null,
                  'msg'=>null,
                  'dataInfo'=>$dataInfo
            ];  
         }
         else{
              $responseData=[
                  'errMsgFlag'=>true,
                  'msgFlag'=>false,
                  'errMsg'=>'Requested Data Not Found.',
                  'msg'=>null,
                  'dataInfo'=>$dataInfo
            ];
         }
  
         return response()->json($responseData,200);
    }

    public function updateBrand(Request $request)
    {
       

          
        
            $exists =SellerBrand::where('id',$request->dataId)->first();
            if($exists){

                $dataInfo = SellerBrand::where('id',$request->dataId)->first();
                $dataInfo->seller_id=Auth::guard('seller-api')->user()->id;

                $dataInfo->save();
                if( $this->sellerInfoUpdate($request,$dataInfo)){
                    DB::commit();
                    $responseData=[
                        'errMsgFlag'=>false,
                        'msgFlag'=>true,
                        'msg'=>"Successfully Added Brand.",
                        'errMsg'=>null,
                     ];

                    return response()->json($responseData,200);
                }
                else{

                    DB::rollBack();

                    $responseData=[
                        'errMsgFlag'=>true,
                        'msgFlag'=>false,
                        'msg'=>null,
                        'errMsg'=>'Failed To Add Product.',
                     ];

                    return response()->json($responseData,200);
                }


            }else{
               
                

            }
            
           

       
    }
    protected function nameGenerate($file)
    {
        $name = base64_encode(rand(10000, 99999) . time());
        $name = preg_replace('/[^A-Za-z0-9\-]/', '', $name);
        return strtolower($name) . '.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
    }
    public function brandDocumentImages($image, $count)
    {
        if (isset($image)) {
         
            $imageName = $this->nameGenerate($image);
            if (!Storage::disk('public')->exists('brandsDocument')) {
                Storage::disk('public')->makeDirectory('brandsDocument');
            }
            
            $note_img = Image::make($image)->stream();
            Storage::disk('public')->put('brandsDocument/' . $imageName, $note_img);
            $path = "/storage/app/public/brandsDocument/".$imageName;
            array_push($this->image_files, $path);
        }
    }


    public function saveBrandImage($request,$sellerBrand)
    {

        foreach ($request->images as $i => $image) {

            $this->brandDocumentImages($image, $i);
       
             $sellerDocument = new BrandDocument();
            
       
              $sellerDocument->document =$this->image_files[$i];
              $sellerDocument->document_id = $sellerBrand->id;
              $sellerDocument->seller_id = Auth::guard('seller-api')->user()->id;
         
              $sellerDocument->save();

      }

       return  true;
   
    }

    public function sellerInfo($request,$dataInfo)
    {
        $brandCount=SellerBrand::where('brand_id',$dataInfo->id)->where('category_id',$request->categoryId)->where('seller_id',Auth::guard('seller-api')->user()->id)->count();
      if($brandCount >1){
        return true;

      }else{
        $sellerBrand= new SellerBrand();
        $sellerBrand->brand_id = $dataInfo->id;
        $sellerBrand->seller_id = Auth::guard('seller-api')->user()->id;
        $sellerBrand->category_id = $request->categoryId;
        $sellerBrand->relationType = $request->relationType;
        $sellerBrand->tradMarkNumber = $request->tradMarkNumber;
        $sellerBrand->startDate = $request->startDate;
        $sellerBrand->endDate = $request->endDate;
        $sellerBrand->appliedDate = Carbon::now();
        $sellerBrand->save();
        if($this->saveBrandImage($request,$sellerBrand) ){
            return true;
            
        }

      }
        
      
    }

    public function sellerInfoUpdate($request,$dataInfo)
    {
        $brandCount=SellerBrand::where('id',$request->dataId)->count();
      if($brandCount >1){
        return true;

      }else{
      
        $sellerBrand= SellerBrand::where('id',$request->dataId)->first();
  
        $sellerBrand->seller_id = Auth::guard('seller-api')->user()->id;
        $sellerBrand->relationType = $request->relationType;
        $sellerBrand->category_id = $request->categoryId;
        $sellerBrand->tradMarkNumber = $request->tradMarkNumber;
        $sellerBrand->startDate = $request->startDate;
        $sellerBrand->endDate = $request->endDate;
        $sellerBrand->appliedDate = Carbon::now();
        $sellerBrand->save();
       
        if($this->saveBrandImage($request,$sellerBrand) ){
            return true;
          
            
        }
      }
        
      
    }

    public function getBrandPcList(Request $request)
    {
        $dataList=Brand::where('status',1)->orderBy('name','asc')->whereNull('deleted_at')->get();

        return response()->json($dataList,200);
    }

    public function searchBrandList(Request $request)
    {
		$query = $request->input('q');

		$results = DB::table('brands')
		  ->where('name', 'like', '%'.$query.'%')
		  ->get();
	  
		return response()->json($results);
    }
}
