<?php
/**
 * @SWG\SecurityScheme(
 *   securityDefinition="JWT",
 *   type="apiKey",
 *   in="header",
 *   name="Authorization"
 * )
 * @SWG\Definition(
 *   definition="Date",
 *   @SWG\Property(property="date", type="string", format="dateTime"),
 *   @SWG\Property(property="timezone_type", type="integer"),
 *   @SWG\Property(property="timezone", type="string", default="UTC"),
 * )
 * @SWG\Definition(
 *   definition="Geolocation",
 *   @SWG\Property(property="lat", type="string"),
 *   @SWG\Property(property="lng", type="string"),
 * )
 */